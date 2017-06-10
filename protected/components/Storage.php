<?php

/*
 * Component responsible for handling credentials, authentication and file transactions
 * between DocSystem's Storage Servers
 * 
 * Storage Servers implemented:
 *      Amazon AWS S3
 *      Shared Folder (DocSystem's Windows Server Storage) 
 * 
 * Implementation by:
 *      Vinicius Guedes <vinicius.guedes@docsystemcorp.com>
 * 
 */

if (file_exists(__DIR__ . '/../../vendor/autoload.php'))
    require __DIR__ . '/../../vendor/autoload.php';
else if (file_exists(__DIR__ . '/../vendor/autoload.php'))
    require __DIR__ . '/../vendor/autoload.php';

/**
 * Description of Storage
 *
 * @author Vinicius Guedes <vinicius.guedes@docsystemcorp.com>
 */
class Storage extends CApplicationComponent {

    /**
     * Authenticate on AWS S3
     */
    const AUTH_AWS = 1;

    /**
     * Authenticate on Shared Folder (DocSystem Storage Server)
     */
    const AUTH_SHRDFOLDER = 2;

    /**
     * Authenticate on File System
     */
    const AUTH_FILESYS = 3;

    /**
     * (PHP 5)
     * Indicates what Storage is pointed and authenticated
     * @var Integer
     */
    private $authIn;

    /**
     * (PHP 5)
     * Handle AWS Credential
     * @var AWSS3|SharedFolder
     */
    private $_credential;

    /**
     * (PHP 5)
     *
     * Constructor
     *
     * @param Integer $authIn
     * @param Array $file
     */
    public function __construct($authIn = null) {
        if (is_null($authIn))
            $authIn = self::AUTH_FILESYS;

        $this->changeAuth($authIn); //Insert Authentication
    }

    /**
     * (PHP 5)
     *
     * Change Authentication mode
     *
     * @param Integer $authIn
     */
    public function changeAuth($authIn) {
        //Defines Authentication Server
        $this->authIn = $authIn;

        if ($this->authIn == self::AUTH_FILESYS)
            $this->_credential = new FileSystem;
    }

    /**
     * (PHP 5)
     *
     * Give access to Storage Server defined
     *
     * @return AWSS3|SharedFolder
     */
    public function server() {
        if (!is_null($this->_credential))
            return $this->_credential;
        else
            return null;
    }

    /**
     * (PHP 5)
     *
     * Remove files cached from Application Storage
     *
     * @param String $preffix
     * @return Boolean
     */
    public function removeFileFromCache($preffix) {
        $dir = str_replace("\\", "/", __DIR__);

        //Document cache download directory
        $downloadDir = "{$dir}/../../Downloads/";

        //Run through cache files
        foreach (scandir($downloadDir) as $file) {
            $filePreffix = substr($file, 0, strlen($preffix));
            if ($filePreffix == $preffix)
                unlink("{$dir}/../../Downloads/{$file}");
        }

        return true;
    }

}

/**
 * Description of Server
 *
 * @author Vinicius Guedes <vinicius.guedes@docsystemcorp.com>
 */
class Server {

    /**
     * Handles $_FILES
     * @var Array
     */
    protected $_file;

    /**
     *
     * @var String
     */
    protected $_dir;

    /**
     * Availables types for conversion to PDF
     * @var Array
     */
    protected $_2PDF = ['docx', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'dwg'];

    /**
     * (PHP 5)
     *
     * Construtor
     */
    public function __construct() {
        $this->_dir = str_replace("\\", "/", __DIR__);
    }

    /**
     * (PHP 5)
     *
     * Returns Workspace Repository name
     *
     * @return String
     */
    public function getRepositoryName() {
        $workspace = Yii::app()->session->get('WorkSpace', null);

        if (is_null($workspace))
            $workspace = explode(".", Yii::app()->request->getServerName())[0];

        return "docsystem_dscloud_" . strtoupper(md5($workspace));
    }

    /**
     * (PHP 5)
     *
     * Set a file to be manipulated
     *
     * @param $_FILES $file
     */
    public function setFile($file) {
        $this->_file = $file;
    }

    /**
     * (PHP 5)
     *
     * Returns manipulated File
     *
     * @return $_FILES
     */
    public function getFile() {
        return $this->_file;
    }

    /**
     * (PHP 5)
     *
     * Get defined file extension
     *
     * @param String $file File path
     * @return String
     */
    public function getFileExtension($file) {
        return array_reverse(explode(".", basename($file)))[0];
    }

    /**
     * (PHP 5)
     * 
     * Create folder path in case it doesn't exists
     * 
     * @param String $path
     * @return Boolean
     */
    public function createPath($path) {
        //Run through each folder on given path and create if it not exists
        $file = "";
        foreach (explode("/", str_replace("\\", "/", $path)) as $folder) {
            $file .= "{$folder}/";

            //Create folder and give permission
            if ($file !== '/' && !is_dir($file))
                mkdir($file, 0777);
        }

        return true;
    }

    /**
     * (PHP 5)
     * 
     * Generates a thumbnail for the given file
     * 
     * @param String $filename
     * @return String
     */
    public function createThumbnail($filename) {
        $ext = $this->getFileExtension($filename);

        if (in_array($ext, $this->_2PDF) || $ext == 'pdf') {
            $filename = array_reverse(explode(".", $filename));

            if (isset($filename[0]))
                $filename[0] = "pdf";

            $filename = implode(".", array_reverse($filename));

            $shell = "convert -density 400 \"" . __DIR__ . "/../../Downloads/{$this->getRepositoryName()}_" . basename($filename) . "[0]\" ";
            $shell .= "-background White -resize 152x172 \"" . __DIR__ . "/../../Downloads/{$this->getRepositoryName()}_";
            $shell .= substr(basename($filename), 0, -3) . "png\"";

            shell_exec($shell);
            return substr($filename, 0, -3) . "png";
        } else if ($ext == "tiff") {
            return str_replace($this->getFileExtension($filename), "png", $filename);
        }

        return null;
    }

    /**
     * (PHP 5)
     *
     * Convert files to determined if file is available for conversion
     *
     * @param String $filename Path to file inside server
     * @return String Path to converted file
     */
    public function convertFile($filename) {
        $file = $this->getRepositoryName() . "_" . basename($filename);

        //Downloads file
        $this->downloadFile($filename, $file, false);

        //If file is permitted format, convert to PDF
        if (in_array($this->getFileExtension($file), $this->_2PDF))
            return $this->convert2PDF($file);
        else if ($this->getFileExtension($file) == 'tiff')
            return $this->convertTiff2Image($file);

        return $file;
    }

    /**
     * (PHP 5)
     *
     * Convert files to PDF
     *
     * @param String $file Path to file inside server
     * @return String Path to converted file
     */
    private function convert2PDF($file) {
        $pdfFile = str_replace($this->getFileExtension($file), "pdf", $file);

        //Check if file is already converted
        if (!file_exists("{$this->_dir}/../../Downloads/{$pdfFile}")) {
            //Check operational system
            if (strcasecmp(PHP_OS, "Linux") == 0)
                $shell = "soffice ";
            else
                $shell = "\"C:/Program Files (x86)/LibreOffice 4/program/soffice.exe\" ";

            $shell .= "-headless -convert-to pdf -outdir \"{$this->_dir}/../../Downloads\" ";
            $shell .= "\"{$this->_dir}/../../Downloads/{$file}\"";

            if (strcasecmp(substr(PHP_OS, 0, 3), "WIN") == 0)
                $shell = str_replace("/", "\\", $shell);

            $response = shell_exec($shell);
        }

        //If file is converted get PDF path
        if (!isset($response) || !is_null($response))
            $file = $pdfFile;

        return $file;
    }

    /**
     * (PHP 5)
     * 
     * Convert TIFF files to Image Format
     * 
     * @param String $file
     * @return String
     */
    private function convertTiff2Image($file) {
        $imageFile = str_replace($this->getFileExtension($file), "png", $file);

        if (!file_exists("{$this->_dir}/../../Downloads/{$imageFile}")) {
            $shell = "convert \"" . __DIR__ . "/../../Downloads/{$file}\" ";
            $shell .= "\"" . __DIR__ . "/../../Downloads/" . $imageFile . "\"";
            $response = shell_exec($shell);
        }

        return $imageFile;
    }


    /**
     *
     *  (PHP 5)
     *
     *  This is a method storage information, this return information for the files:
     *  Storage Size, Files Extension Total, Total Files Storage
     * @return array
     *
     */

    public function getDataRepository()
    {
        $repo = $this->getRepositoryName();
        $this->createRepository($repo);
        $dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("/mnt/storage/{$repo}"));

        $response = [
            'StorageSize' => 0,
            'FilesExtensionAmount' => [],
            'FilesTotalSizeByExtension' => [],
            'TotalFilesStorage'   => 0
        ];


        foreach ($dir as $f) {
            if ($f->isFile()) {
                if (!isset($response['FilesExtensionAmount'][$f->getExtension()]))
                    $response['FilesExtensionAmount'][$f->getExtension()] = 0;

                if (!isset($response['FilesTotalSizeByExtension'][$f->getExtension()]))
                    $response['FilesTotalSizeByExtension'][$f->getExtension()] = 0;

                // Storage Size
                $response['StorageSize'] += $f->getSize(); //File size in bytes

                // Files Amount by Extension
                $response['FilesExtensionAmount'][$f->getExtension()]++;

                $response['FilesTotalSizeByExtension'][$f->getExtension()] += $f->getSize();

                // Total Files in Storage
                $response['TotalFilesStorage'] += count($f->getFileName());

            }
        }




       return $response;

    }

    /**
     * (PHP 5)
     * 
     * Gets Storage space used by current Workspace and return in the measure unity given type.
     * If defined a type that function doesn't support the result is returned in bytes.
     * 
     * @param String $measure_unity Can be KB, MB, GB, TB
     * @return Float
     */
    public function getRepositoryUsedSpace($measure_unity = 'KB') {
        //Get database used space
        $response = $this->getDataBaseUsedSpace();

        //Get Storage Sizes
        $response += $this->calculateStorageSize("/mnt/storage/" . $this->getRepositoryName());

        //Convert result to given unity if necessary
        $measures = ['KB', 'MB', 'GB', 'TB'];
        if (in_array($measure_unity, $measures)) {
            foreach ($measures as $unity) {
                $response = $response * 0.001;

                if ($unity === $measure_unity)
                    break;
            }

            return $response;
        } else
            return $response;
    }



    /**
     *  Return the amounty espace used for Database
     *
     * @param string $measure_unity
     * @return int
     */
    public function getDataBaseUsedSpace()
    {
        $response = 0;
        //Get Database Size
        foreach (Yii::app()->db->createCommand("SHOW TABLE STATUS")->queryAll() as $table)
            $response += ($table['Data_length'] + $table['Index_length']);

        return $response;
    }



}

/**
 *
 * ###### #     # ######
 * ###### ##   ## ######
 * ##  ## ##   ## ##
 * ##  ## ## # ## ######
 * ###### #######     ##
 * ##  ## ### ### ######
 * ##  ## ##   ## ######
 *
 * Implementation of AWS File Transactions
 *
 */
use Aws\S3\S3Client;

/**
 * Description of AWSS3
 *
 * @author Vinicius Guedes <vinicius.guedes@docsystemcorp.com>
 */
class AWSS3 extends Server {

    /**
     * Contains AccessKey and Secret Key
     * @var Array
     */
    private $credentials = [
        'key' => 'AKIAIFVL3OA2OLNYZEEQ', //AKIAIXGGB7UMS5VKYZPQ
        'secret' => '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' //YN0Tybz1MUlSwaw8HN+QFCH9Vfkw3p8XLobKqVci
    ];

    /**
     * Contains S3 Class
     * @var S3Client
     */
    private $S3Client;

    /**
     * (PHP 5)
     *
     * Constructor
     */
    public function __construct() {
        //Generates an instance of S3 manipulator
        //with given Credentials
        $this->S3Client = S3Client::factory([
                    'credentials' => [
                        'key' => $this->credentials['key'],
                        'secret' => $this->credentials['secret']
                    ]
        ]);

        parent::__construct();
    }

    /**
     * (PHP 5)
     *
     * Creates Repository on Server
     *
     * @param String $name
     * @return Boolean
     */
    public function createRepository($name, $wait = true) {
        $this->S3Client->createBucket(['Bucket' => $name]); //Call Bucket creation
        //Long Polling until Bucket exists
        if ($wait)
            $this->S3Client->waitUntil('BucketExists', ['Bucket' => $name]);

        return true;
    }

    /**
     * (PHP 5)
     *
     * Return a list of Buckets from S3 Server
     *
     * @return Array
     */
    public function getRepositories() {
        $repositories = [];
        foreach ($this->S3Client->listBuckets()->get('Buckets') as $repo)
            $repositories[] = ['name' => $repo['Name'], 'createdOn' => date("Y-m-d H:i:s", strtotime($repo['CreationDate']))];

        return $repositories;
    }

    /**
     * (PHP 5)
     *
     * Pega os dados do Bucket da Workspace
     *
     * @return Array
     */
    public function getRepository($repo = null) {
        if (is_null($repo))
            $repo = $this->getRepositoryName();

        try {
            $items = [];
            foreach ($this->S3Client->getIterator('ListObjects', ['Bucket' => $repo]) as $item)
                $items[] = $item;

            return $items;
        } catch (Exception $ex) {
            return [];
        }
    }

    /**
     * (PHP 5)
     *
     * Verifies if given Repository exists
     *
     * @param String $repo
     * @return Boolean
     */
    public function repositoryExists($repo) {
        return $this->S3Client->doesBucketExist($repo);
    }

    /**
     * (PHP 5)
     *
     * Uploads File to Server
     *
     * @param String $fileName Name that file will be stored on server
     * @param String $tmpFile Temporary path to file
     * @return Boolean
     */
    public function uploadFile($fileName, $tmpFile) {
        $repository = $this->getRepositoryName();

        try {
            //Creates Repository (Bucket) if not exists
            if (!$this->repositoryExists($repository))
                $result = $this->createRepository($repository);

            //Upload file
            $this->S3Client->putObject([
                'Bucket' => $repository,
                'Key' => $fileName,
                'Body' => fopen($tmpFile, 'r+')
            ]);

            $response = true;
        } catch (Exception $ex) {
            $response = false;
        }

        return $response;
    }

    /**
     * (PHP 5)
     *
     * Downloads File from Server
     *
     * @param String $filePath Path to file inside Server
     * @param String $newName Name that file should have when downloaded
     * @param Boolean $serveFile Check if file will be downloaded on client
     */
    public function downloadFile($filePath, $newName = null, $serveFile = true) {
        if (is_null($newName))
            $newName = basename($filePath);

        //Creates download directory if not exists
        $downloadDir = "{$this->_dir}/../../Downloads/";
        if (!is_dir($downloadDir))
            mkdir($downloadDir, 0777);

        //Defines file name
        $newName = $downloadDir . $newName;

        //Download file if isn't saved inside Project
        if (!file_exists($newName))
            $this->S3Client->getObject(['Bucket' => $this->getRepositoryName(), 'Key' => $filePath, 'SaveAs' => $newName]);

        //If true force file to be downloaded on client
        if ($serveFile) {

            //Gets file info
            $fileName = basename($newName);
            $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $newName);

            //Force download
            header("Content-Type: {$mimeType}");
            header("Content-Disposition: attachment; filename=\"{$fileName}\"");
            header("Pragma: public");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

            //Print file content
            ob_flush();
            flush();
            readfile($newName);

            //Deletes file from application server
            unlink($newName);
        }
    }

}

/**
 * 
 * Implementation of FileSystem File Transactions
 * 
 * @author Vinicius Guedes <vinicius.guedes@docsystemcorp.com>
 */
class FileSystem extends Server {

    /**
     * (PHP 5)
     *
     * Creates Repository on Server
     *
     * @param String $name
     * @return Boolean
     */
    public function createRepository($name) {
        $dirName = Yii::app()->params['localStorageDir'] . $name;

        if (!$this->repositoryExists($name))
            mkdir($dirName);

        return true;
    }

    /**
     * (PHP 5)
     *
     * Return a list of Repositories
     *
     * @return Array
     */
    public function getRepositories() {
        $repositories = [];
        foreach (scandir(Yii::app()->params['localStorageDir']) as $repo)
            $repositories[] = ['name' => $repo];

        return $repositories;
    }

    /**
     * (PHP 5)
     *
     * Gets defined Bucket Items
     *
     * @return Array
     */
    public function getRepository($repo = null) {
        if (is_null($repo))
            $repo = $this->getRepositoryName();

        $repository = Yii::app()->params['localStorageDir'] . $repo;
    }



    /**
     * (PHP 5)
     *
     * Verifies if given Repository exists
     *
     * @param String $repo
     * @return Boolean
     */
    public function repositoryExists($repo) {
        return is_dir(Yii::app()->params['localStorageDir'] . $repo);
    }

    /**
     * (PHP 5)
     *
     * Uploads File to Server
     *
     * @param String $fileName Name that file will be stored on server
     * @param String $tmpFile Temporary path to file
     * @return Boolean
     */
    public function uploadFile($fileName, $tmpFile) {
        $repository = $this->getRepositoryName();
        $this->createRepository($repository); //Create Repository if not exists
        
        //Verifies if ECM Folder exists inside Repository
        //In case it not exists it's created
        $targetFile = Yii::app()->params['localStorageDir'] . "{$repository}/{$fileName}";

        if (!is_dir(str_replace(basename($targetFile), "", $targetFile)))
            $this->createPath(dirname($targetFile));

        //Makes upload and return Response
        return move_uploaded_file($tmpFile, $targetFile);
    }

    /**
     * (PHP 5)
     *
     * Downloads File from Server
     *
     * @param String $filePath Path to file inside Server
     * @param String $newName Name that file should have when downloaded
     * @param Boolean $serveFile Check if file will be downloaded on client
     */
    public function downloadFile($filePath, $newName = null, $serveFile = true) {
        if (is_null($newName))
            $newName = basename($filePath);

        //Creates download directory if not exists
        $downloadDir = "{$this->_dir}/../../Downloads/";
        if (!is_dir($downloadDir))
            mkdir($downloadDir, 0777);

        //Defines file name
        $newName = $downloadDir . $newName;

        //Checks if File exists
        if (!file_exists($newName))
            copy(Yii::app()->params['localStorageDir'] . "{$this->getRepositoryName()}/{$filePath}", $newName);

        //If true force file to be downloaded on client
        if ($serveFile) {

            //Gets file info
            $fileName = basename($newName);
            $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $newName);

            //Force download
            header("Content-Type: {$mimeType}");
            header("Content-Disposition: attachment; filename=\"{$fileName}\"");
            header("Pragma: public");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

            //Print file content
            ob_flush();
            flush();
            readfile($newName);

            //Deletes file from application server
            unlink($newName);
        }
    }

    /**
     * (PHP 5)
     * 
     * Calculate storage size
     * 
     * @param String $path
     * @return Float|Integer
     */
    protected function calculateStorageSize($path) {
        if (!file_exists($path))
            return 0;

        if (is_file($path))
            return filesize($path);

        $return = 0;
        foreach (glob($path . "/*") as $fn)
            $return += $this->calculateStorageSize($fn);

        return $return;
    }

}

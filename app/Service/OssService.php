<?php
declare(strict_types=1);

namespace App\Service;

use OSS\Core\OssException;
use OSS\OssClient;
use Exception;

class OssService
{
    /**
     * @var string
     */
    protected $accessKeyId;

    /**
     * @var string
     */
    protected $accessKeySecret;

    /**
     * @var string
     */
    protected $bucket;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var bool
     */
    protected $isCName;

    /**
     * @var bool
     */
    protected $ssl;

    /**
     * @var string
     */
    protected $cdnDomain;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var OssClient
     */
    private $ossClient;


    /**
     * 初始化 API
     *
     * OssService constructor.
     * @throws Exception
     */
    public function __construct ()
    {
        $config                = config('file.storage.oss');
        $this->accessKeyId     = $config['accessId'];
        $this->accessKeySecret = $config['accessSecret'];
        $this->bucket          = $config['bucket'];
        $this->endpoint        = $config['endpoint'];
        $this->isCName         = $config['is_cname'];
        $this->ssl             = $config['ssl'];
        $this->cdnDomain       = $config['cdn_domain'];
        $this->debug           = $config['debug'];
        try {
            $endpoint = $this->endpoint;
            $this->ossClient = new OssClient(
                $this->accessKeyId,
                $this->accessKeySecret,
                $endpoint,
                $this->isCName
            );
        } catch (OssException $e) {
            logger()->error("oss 客户端连接失败：" .$e->getMessage() . "\n");
            return;
        }
    }

    /**
     * 上传文件
     *
     * @param string $ossFileName
     * @param string $fileName
     * @return bool|string
     */
    public function upload(string $ossFileName, string $fileName)
    {
        try {
            $content = file_get_contents($fileName);
            $ret = $this->ossClient->putObject($this->bucket, $ossFileName, $content);
            if ($ret) {
                if ($this->debug)
                    logger()->debug("上传OSS结果：" . json_encode($ret));
                if ($ret['info']['http_code'] == 200) {
                    $url = $this->getBaseUri() . '/' . $ossFileName;

                    return $url;
                }
            }
        } catch (OssException $e) {
            logger()->error("上传结果OSS异常：" . $e->getMessage(). '文件：'.$fileName);
        }

        return false;
    }

    /**
     * 获取Base uri函数
     */
    public function getBaseUri()
    {
        if ($this->ssl) { // 请求头
            $baseUri = "https://";
        } else {
            $baseUri = "http://";
        }

        $baseUri .= $this->cdnDomain ? $this->cdnDomain : $this->bucket . '.' . $this->endpoint; //域名

        return $baseUri;
    }

    /**
     * @param string $ossFileName
     * @return string
     */
    public function getUrl(string $ossFileName)
    {
        $baseUri = $this->getBaseUri();

        $url = $baseUri.'/'.$ossFileName;

        return $url;
    }

    /**
     * 删除OSS文件
     * @param string $ossFileName
     * @return null
     */
    public function delete(string $ossFileName)
    {
        try {
            if($this->ossClient->doesObjectExist($this->bucket, $ossFileName)) {
                return $this->ossClient->deleteObject($this->bucket, $ossFileName);
            }
        } catch (\Exception $e) {
            logger()->error('删除文件移除: ' . $e->getMessage() . '[文件]'.$ossFileName);
        }

        return false;
    }
}



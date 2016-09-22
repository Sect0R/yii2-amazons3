<?php
namespace Sect0R\yii2amazons3;

use Aws\S3\S3Client;
/**
 * A Yii2-compatible component wrapper for Aws\S3\S3Client.
 * Just add this component to your configuration providing this class,
 * key, secret and bucket.
 *
 * ~~~
 * 'components' => [
 *     'storage' => [
 *          'class' => '\jovanialferez\yii2s3\AmazonS3',
 *          'key' => 'AWS_ACCESS_KEY_ID',
 *          'secret' => 'AWS_SECRET_ACCESS_KEY',
 *          'bucket' => 'YOUR_BUCKET',
 *     ],
 * ],
 * ~~~
 *
 * You can then start using this component as:
 *
 * ```php
 * $storage = \Yii::$app->storage;
 * $url = $storage->uploadFile('/path/to/file', 'unique_file_name');
 * ```
 *
 */
class AmazonS3 extends \yii\base\Component
{
    public $bucket;
    public $key;
    public $secret;

    protected $_client;

    public function init()
    {
        parent::init();

        $this->_client = \Aws\S3\S3Client::factory([
            'key' => $this->key,
            'secret' => $this->secret,
        ]);
    }

    /**
     * Uploads the file into S3 in that bucket.
     *
     * @param string $filePath Full path of the file. Can be from tmp file path.
     * @param string $fileName Filename to save this file into S3. May include directories.
     * @param bool $bucket Override configured bucket.
     * @return bool|string The S3 generated url that is publicly-accessible.
     */
    public function uploadFile($filePath, $fileName, $bucket = false)
    {
        if (!$bucket) {
            $bucket = $this->bucket;
        }

        try {
            $result = $this->_client->putObject([
                'ACL' => 'public-read',
                'Bucket' => $bucket,
                'Key' => $fileName,
                'SourceFile' => $filePath,
                'ContentType' => \yii\helpers\FileHelper::getMimeType($filePath),
            ]);

            return $result->get('ObjectURL');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * This operation enables you to delete multiple objects from a bucket using a single HTTP request. You may specify up to 1000 keys.
     * @param array $keysToDelete [1,2,3]
     * @param bool|false $bucket - if is false use current bucket
     * @return bool
     */
    public function deleteObjects($keysToDelete, $bucket = false) {
        if (!$bucket) {
            $bucket = $this->bucket;
        }

        $keys = [];
        foreach ( $keysToDelete as $key ) {
            $keys[] = ['Key' => $key];
        }

        try {
            $result =  $this->_client->deleteObjects(array(
                'Bucket'  => $bucket,
                'Objects' => $keys
            ));

            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Removes the null version (if there is one) of an object and inserts a delete marker, which becomes the latest version of the object.
     * If there isn't a null version, Amazon S3 does not remove any objects.
     * @param integer $key - object key
     * @param bool|false $bucket - if is false use current bucket
     * @return bool
     */
    public function deleteObject($key, $bucket = false) {
        if (!$bucket) {
            $bucket = $this->bucket;
        }

        try {
            $result =  $this->_client->deleteObject(array(
                'Bucket'  => $bucket,
                'Key' => $key
            ));

            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Returns some or all (up to 1000) of the objects in a bucket. You can use the request parameters as selection criteria to return a
     * subset of the objects in a bucket.
     * @param bool|false $bucket - if is false use current bucket
     * @return bool
     */
    public function listObjects($bucket = false) {
        if (!$bucket) {
            $bucket = $this->bucket;
        }

        try {
            $result =  $this->_client->listObjects(array(
                'Bucket'  => $bucket,
            ));

            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Returns metadata about all of the versions of objects in a bucket.
     * @param bool|false $keyMarker - Specifies the key to start with when listing objects in a bucket.
     * @param bool|false $bucket - if is false use current bucket
     * @return bool
     */
    public function listObjectVersions($keyMarker = false, $bucket = false) {
        if (!$bucket) {
            $bucket = $this->bucket;
        }

        try {
            $request = ['Bucket' => $bucket];
            if ( $keyMarker )
                $request['KeyMarker'] = $keyMarker;

            $result =  $this->_client->ListObjectVersions($request);

            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Deletes objects from Amazon S3 that match the result of a ListObjects operation. For example,
     * this allows you to do things like delete all objects that match a specific key prefix.
     *
     * @param string $prefix - Optionally delete only objects under this key prefix
     * @param string $regex - Delete only objects that match this regex
     * @param bool|false $bucket - if is false use current bucket
     * @param array $options - Options used when deleting the object: - before_delete: Callback to invoke before each delete.
     * @return bool
     */
    public function deleteMatchingObjects($prefix = '', $regex = '', $bucket = false, array $options = array()) {
        if (!$bucket) {
            $bucket = $this->bucket;
        }

        try {
            $result = $this->_client->deleteMatchingObjects($bucket, $prefix, $regex, $options);
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }
}
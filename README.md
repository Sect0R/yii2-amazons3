# yii2-amazons3
=======
Amazon S3 Client wrapper as Yii2 component.

**Usage**

```php
return [
	// ...
	'components' => [
		'storage' => [
			'class' => '\Sect0R\yii2-amazons3\AmazonS3',
			'key' => 'AWS_ACCESS_KEY_ID',
			'secret' => 'AWS_SECRET_ACCESS_KEY',
			'bucket' => 'YOUR_BUCKET',
		],
		// ...
	],
];
```
You can then start using this component as:

```php
$storage = \Yii::$app->storage;

// Uploads the file into S3 in that bucket.
$url = $storage->uploadFile('/path/to/file', 'unique_file_name');

// Delete multiple objects from a bucket using a single HTTP request. You may specify up to 1000 keys.
$result = $storage->deleteObjects([131234,121421]);

// Removes the null version (if there is one) of an object and inserts a delete marker, which becomes the latest version of the object.
$result = $storage->deleteObject(131234);

// Returns some or all (up to 1000) of the objects in a bucket.
$objects = $storage->listObjects();

// Returns metadata about all of the versions of objects in a bucket.
$keys = $storage->listObjectVersions();

// Deletes objects from Amazon S3 that match the result of a ListObjects operation.
$result = $storage->deleteMatchingObjects('thumbnail-');

```

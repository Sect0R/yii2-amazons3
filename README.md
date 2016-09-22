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

// uploadFile() here returns the resulting S3 url of that file
$url = $storage->uploadFile('/path/to/file', 'unique_file_name');
```

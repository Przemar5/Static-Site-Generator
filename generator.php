<?php


function getParam(string $name, array $params)
{
	if ($key = array_search($name, $params)) {
		return $params[$key+1];
	}
}

function createOutputDir(string $name)
{
	if (!file_exists('./'.$name)) {
		mkdir('./'.$name);
	}
}

function generateSingle(string $baseUri, string $pathInfo, string $outputDir)
{
	$pathInfo = trim($pathInfo, '/');
	$pathParts = explode('/', $pathInfo) ?? [];
	$filename = array_pop($pathParts);
	if ($filename == '')
		$filename = 'index.php';
	$path = implode('/', $pathParts);
	$content = getResponseForUri("$baseUri/$path/$filename");
	$filename = explode('.', $filename);
	$filename = $filename[0].'.html';
	createFile($outputDir.'/'.$path, $filename, $content);
}

function createDir(string $path)
{
	$dirs = explode('/', $path);
	$current = '';

	foreach ($dirs as $dir) {
		$current = $current.DIRECTORY_SEPARATOR.$dir;
		if (!file_exists($current)) {
			mkdir('./'.$current);
		}
	}
}

function createFile(string $path, string $filename, ?string $content = '')
{
	createDir($path);
	file_put_contents('./'.$path.DIRECTORY_SEPARATOR.$filename, $content);
}

function getResponseForUri(string $uri)
{
	$ch = curl_init($uri);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	return curl_exec($ch);
}


if (file_exists('./generator.json')) {
	$outputDir = getParam('--output-dir', $argv) ?? 'output';
	$config = file_get_contents('./generator.json');
	$config = json_decode($config);

	foreach ($config->pages as $page) {
		generateSingle($config->baseUri, $page, $outputDir);
	}
}
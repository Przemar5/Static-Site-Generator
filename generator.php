<?php

$acceptedParams = ['--output-dir'];

function extractParam(array $possible, string $name, ?string $value) {
	if (in_array($name, $possible)) {
		return $value;
	}
}

function getParam(string $name, array $params)
{
	if ($key = array_search($name, $params)) {
		return $params[$key+1];
	}
}
// echo $argv[1];

// extractParam($acceptedParams, $argv[1], $argv[2] ?? '');
$outputDir = getParam('--output-dir', $argv) ?? 'output';
// createOutputDir($outputDir);

// Create folder
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

// createFile($outputDir.'/alan/turing', 'tekst.txt');

if (file_exists('./generator.json')) {
	$config = file_get_contents('./generator.json');
	$config = json_decode($config);

	foreach ($config->pages as $page) {
		generateSingle($config->baseUri, $page, $outputDir);
	}
}
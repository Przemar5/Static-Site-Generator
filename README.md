# Static-Site-Generator


## Usage

Create `generator.json` file in root directory like below:

`{
	"baseUri": "http://example.com",
	"pages": [
		"/example"
	]
}`

Then run `generator.json` file in command line

Next step is redirecting all requests into output directory


## Flags

For now there exists only one flag `--output-dir`, which by default is set to 'output'. If directory was created before, output will be inserted into this directory. If you want to specify other directory, try command below:

`php generator.json --output-dir "some-directory"`
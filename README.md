# Static-Site-Generator


## Usage

Create `generator.json` file in root directory like below:
`{
	"baseUri": "http://example.com",
	"pages": [
		"/example"
	]
}`

Run `generator.json` file in command line


## Flags

For now there exists only one flag `--output-dir`, which by default is set to `output`. Your command should look like below:
`php generator.json --output-dir "some-directory"`
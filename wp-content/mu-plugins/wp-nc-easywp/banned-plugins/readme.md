## List of banned plugin

* All file in this folder must be a `.json` to be process
* The file `banned-plugins.json` is from https://github.com/presslabs/banned-plugins/blob/master/banned-plugins.json

## Format

```json
{
  "plugins": [
    {
      "file": "varnish-http-purge/varnish-http-purge.php",
      "reason": "We have our own cache system.",
      "description": "We have our own cache system."
    },
    ...
  ]
}

```

## Update

Run `php bones banned:plugins`
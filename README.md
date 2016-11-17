# Dynamic Layout
Web-based layout for OBS Studio.

## Requirements
All you need is a bundled version of [Open Broadcaster Software][rel] and Browser Source. From the GitHub releases page make sure to download the full installer.

## Installation
After cloning the repository to your server, create configuration file `config.json`.

```bash
# Copy the example configuration
cp example.json config.json
# Fill in the correct values
vim config.json
````

## Development
Write new code, test it and open a pull request. The easiest way to debug this locally is with [Python 3][python3].

```bash
cd /path/to/project
python -m http.server
# Now point your browser or OBS to http://localhost:8000
```

Under the section `config` you need to enter your Twitch channel name and client ID and the [API key][mashape] from Mashape.

## Usage
1. Add _BrowserSource_ to your OBS Scene
2. Type in the URL leading to your server (eg. https://example.com/layout)
3. Set width and height to 1920px and 1080px respectively

### Parameters

URL mentioned above is constructed of different GET parameters. Here they are documented.

- `art`: set to `1` to enable cover arts
- `aspect`: aspect ratio for the layout
    - default is **16:9**
    - use `retro` for **4:3**
- `player`: key for the player name in _config.json_
- `scale`: set from `0.0` to `1.0` for browser previews

**Example**  
https://example.com/?player=simon&aspect=retro&art=1

## Support
Ask help from arkkis or njh in [Slack][slack]. For bugs, open a new issue.

[rel]: https://github.com/jp9000/obs-studio/releases
[mashape]: https://www.igdb.com/api
[python3]: https://www.python.org/downloads/
[slack]: https://slack.pelilegacy.fi

# Dynamic Layout
Web-based layout for OBS Studio.

## Requirements
All you need is a bundled version of [Open Broadcaster Software][obs] and Browser Source.

[Check here][rel] for the latest releases of OBS Multiplatform.

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

Under the section `config` you need to enter your Twitch channel name and client ID, [API key][gb] from GiantBomb, and the URL for TwitchAlerts.

## Usage
1. Add _BrowserSource_ to your OBS Scene
2. Type in the URL leading to your server (eg. https://example.com/layout)
3. Set width and height to 1920px and 1080px respectively

## Support
Ask help from @arkkis or @njh in Slack. For bugs, open an issue.

[obs]: https://github.com/jp9000/obs-studio/releases/download/0.14.2/OBS-Studio-0.14.2-With-Browser-Installer.exe
[rel]: https://github.com/jp9000/obs-studio/releases
[gb]: http://www.giantbomb.com/api/
[python3]: https://www.python.org/downloads/

# Dynamic Layout

Web-based layout for OBS Multiplatform.

## Requirements
Most of the working web servers will do. It's recommended to have the newest Apache or nginx set up and running just in case.

Be sure to download the correct version of [Open Broadcaster Software](https://github.com/jp9000/obs-studio/releases/download/0.13.1-rc1/OBS-MP-0.13.1-rc1-With-Browser-Installer.exe) and the [Browser Plugin](https://obsproject.com/forum/resources/browser-plugin.115/) (bundled with the installer).

[Check here](https://github.com/jp9000/obs-studio/releases) for the latest releases of OBS Multiplatform.

## Installation
After cloning the repository to your server, create configuration file `config.json`.

```bash
# Copy the example configuration
cp example.json config.json
# Fill in the correct values
vim config.json
```

Under the section `config` you need to enter your Twitch channel name, [API key][gb] from GiantBomb and the URL for TwitchAlerts.

## Usage
1. Add _BrowserSource_ to your OBS Scene
2. Type in the URL leading to your server (eg. https://example.com/layout)
3. Set width and height to 1920px and 1080px respectively

[gb]: http://www.giantbomb.com/api/

# Parser for flats sites
Parser works with Belarusian flats sites. It can send emails to you with parsed information. Telegram bot implementation will comes soon.

#### List of supported sites
* kvartirant.by
* neagent.by
* onliner.by

but you can add more. To do this, check [Add site section](##How-to-ddd-site)

## Installation steps
#### Clone repo
```bash
git clone https://github.com/Clerick/flats-parser.git
```
#### Install dependencies
```bash
composer install
```
### Parser uses [Selenium webdriver](https://github.com/facebook/php-webdriver), so make sure you have all it's dependencies

## How to add site
Create new class in `Models\Sites` folder. Inherit base class called AbstractSite and implement all it's methods and properties. Controller will automaticly check the folder and will include this site class while parse.



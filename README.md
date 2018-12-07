<p align="center">
  <a href="https://packagist.org/packages/fwartner/laravel-installer"><img src="https://poser.pugx.org/fwartner/laravel-installer/d/total.svg" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/fwartner/laravel-installer"><img src="https://poser.pugx.org/fwartner/laravel-installer/v/stable.svg" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/fwartner/laravel-installer"><img src="https://poser.pugx.org/fwartner/laravel-installer/license.svg" alt="License"></a>
</p>

<h4> <center>This is a <bold>community project</bold> and not an official Laravel one </center></h4>

## What is Laravel Installer?

Laravel installer includes all installers from the official laravel repositories such as for:

- Laravel
- Lumen
- Spark

## Installation

To install the laravel-installer globally using composer, simply type `composer global require fwartner/laravel-installer` and you´re good to go.

#### Installation Note

Please make sure that you have removed `laravel/installer` from your global `composer.json` using the following command:

`composer global remove laravel/installer`

(This is only necessary if you have the official installer on your machine)

## Usage

Open up your terminal and type `laravel`.

```
 _                               _   _____           _        _ _
| |                             | | |_   _|         | |      | | |
| |     __ _ _ __ __ ___   _____| |   | |  _ __  ___| |_ __ _| | | ___ _ __
| |    / _` | '__/ _` \ \ / / _ \ |   | | | '_ \/ __| __/ _` | | |/ _ \ '__|
| |___| (_| | | | (_| |\ V /  __/ |  _| |_| | | \__ \ || (_| | | |  __/ |
|______\__,_|_|  \__,_| \_/ \___|_| |_____|_| |_|___/\__\__,_|_|_|\___|_|



  1.0.0

  USAGE: laravel <command> [options] [arguments]

  new:laravel    Create a new Laravel application
  new:lumen      Create a new Lumen application
  new:spark      Create a new Spark application

  spark:register Register an API token with the installer
  spark:token    Display the currently registered Spark API token
```

## Roadmap

- [X] Implement laravel installer
- [X] Implement lumen installer
- [X] Implement spark installer
- [ ] Implement template installer
- [ ] Implement dependency installer
- [ ] Add tests

------

## Support the development
**Do you like this project? Support it by donating**

- PayPal: [Donate](https://www.paypal.me/florianwartner)
- Patreon: [Donate](https://www.patreon.com/fwartner)

## License

Laravel Installer is an open-source software licensed under the [MIT license](https://github.com/fwartner/laravel-installer/blob/stable/LICENSE.md).

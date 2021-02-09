# Gendiff console app

![hexlet-check](https://github.com/reymezis/php-project-lvl2/workflows/hexlet-check/badge.svg)
[![Github Actions Status](https://github.com/reymezis/php-project-lvl2/workflows/PHP%20CI/badge.svg)](https://github.com/reymezis/php-project-lvl2/actions)
[![Code Climate](https://api.codeclimate.com/v1/badges/9f7ce5c33523e84a3b67/maintainability)](https://codeclimate.com/github/reymezis/php-project-lvl2/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/9f7ce5c33523e84a3b67/test_coverage)](https://codeclimate.com/github/reymezis/php-project-lvl2/test_coverage)


## Setup

```sh
$ git clone https://github.com/reymezis/php-project-lvl2.git

$ make install
```

## Run tests

```sh
$ make test
```

## Usage
 
```sh
$ gendiff -h
```
[![asciicast](https://asciinema.org/a/rbSiqt31KMitj8FcMBqd9kfw4.svg)](https://asciinema.org/a/rbSiqt31KMitj8FcMBqd9kfw4)

plain files diff
```sh
$ gendiff file1.json file2.json
```
[![asciicast](https://asciinema.org/a/9tRKEILjup95YG1Wujf3JX6qa.svg)](https://asciinema.org/a/9tRKEILjup95YG1Wujf3JX6qa)

nested files diff with stylish output format
```sh
$ gendiff file1.yml file2.yml
```
[![asciicast](https://asciinema.org/a/n0lU6riWscPKbcNsHxdpTbjxb.svg)](https://asciinema.org/a/n0lU6riWscPKbcNsHxdpTbjxb)

nested files diff with plain output format
```sh
$ gendiff --format plain file1.yml file2.yml
```

[![asciicast](https://asciinema.org/a/XyLfEu8jrcrNtXUctnyi3fFXX.svg)](https://asciinema.org/a/XyLfEu8jrcrNtXUctnyi3fFXX)

nested files diff with json output format
```sh
$ gendiff --format json file1.yml file2.yml
```
[![asciicast](https://asciinema.org/a/l6oIzsmU36aiGmIiaSpcTqwow.svg)](https://asciinema.org/a/l6oIzsmU36aiGmIiaSpcTqwow)
# Mamapp

[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE) 
[![PHP Composer](https://github.com/famoser/mamapp/actions/workflows/php.yml/badge.svg)](https://github.com/famoser/mamapp/actions/workflows/php.yml)
[![Node.js Encore](https://github.com/famoser/mamapp/actions/workflows/node.js.yml/badge.svg)](https://github.com/famoser/mamapp/actions/workflows/node.js.yml)

Identify mammals in the wild.

## Develop

The backend is built using `php` (using the `slim` framework) and manages dependencies using `composer`. Quick start:
- `composer install`
- `symfony server:start` (see [symfony CLI](https://symfony.com/download))

The frontend is built using `JavaScript` (using `vue` with `TypeScript`), manages dependencies using `npm`, and builds using `vite`. Quick start:
- `npm install`
- `npm run dev`

Then open `localhost:5173` (pointing to the `vite` server started by `npm run dev`) to see the webpage. Note that only in this local environment, the `vite` server is running, in production `php` serves all files.

## Release & Deploy

`famoser/agnes` is used to release and deploy.

You need access to the config repository specified in `agnes.yml`. Then:
- create a new release (here `v1.0`) of `main` branch with `./vendor/bin/agnes release v1.0 main`
- deploy release to `prod` environment with `./vendor/bin/agnes deploy *:*:prod v1.0`

The server needs to fulfill requirements specified in `composer.json`.


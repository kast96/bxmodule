# BXModule

[![install size](https://packagephobia.com/badge?p=@kast96/bxmodule)](https://packagephobia.com/result?p=@kast96/bxmodule)

The `bxmodule` package is designed to quickly create a project module for the CMS `1C-Bitrix`.

- [BXModule](#bxmodule)
  - [Installation](#installation)
  - [Quick Start](#quick-start)
  - [Help](#help)

## Installation

Go to modules folder `/bitrix/modules` or `/local/modules`.

```sh
npm install @kast96/bxmodule
```

## Quick Start

Run command for create new module:

```sh
npx bxmodule create company.mymodule
```

Several installation questions will be asked.
Package will create a `company.mymodule` folder in the current folder.

Ready! Now you can install the module through the 1С-Bitrix admin panel.

## Help

You can run the command for help about package commands.

```sh
npx bxmodule --help
```
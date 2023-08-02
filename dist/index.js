#!/usr/bin/env node
"use strict";
const { Command } = require("commander");
const { create } = require("./commands/create");
const packageJson = require('../package.json');
const program = new Command();
program.version(packageJson.version);
program.description("CLI by EWP Company");
program.command('create')
    .argument('<vendor.name>', 'Название модуля. Должено состоять из вендорного кода и кода модуля разделенные точкой: vendor.module')
    .description('Создать модуль Битрикс')
    .action((name) => {
    create(name);
});
program.parse();
//# sourceMappingURL=index.js.map
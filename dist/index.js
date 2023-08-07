#!/usr/bin/env node
"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const { Command } = require("commander");
const packageJson = require('../package.json');
const create_1 = require("./commands/create");
const program = new Command();
program.version(packageJson.version);
program.description("CLI by EWP Company");
program.command('create')
    .argument('<vendor.name>', 'Название модуля. Должено состоять из вендорного кода и кода модуля разделенные точкой: vendor.module')
    .description('Создать модуль Битрикс')
    .action((name) => {
    (0, create_1.create)(name);
});
program.parse();
//# sourceMappingURL=index.js.map
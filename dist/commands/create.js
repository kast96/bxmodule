"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.create = void 0;
const { replaceModuleName } = require("../utils");
const fs = require('fs');
const path = require('path');
const create = (name) => {
    const [vendor, module] = name.split('.');
    if (!vendor || !module) {
        console.log(`Название модуля должено состоять из вендорного кода и кода модуля разделенные точкой: vendor.module`);
        return;
    }
    const inputPath = path.join(__dirname, `../../source`);
    const outputPath = `${vendor}.${module}`;
    const charset = 'utf8';
    createDirectoryPath(path.dirname(`${outputPath}/include.php`));
    fs.writeFileSync(`${outputPath}/include.php`, replaceModuleName(fs.readFileSync(`${inputPath}/include.php`, charset), vendor, module));
    console.log(`Создание модуля ${vendor}.${module} завершено`);
};
exports.create = create;
const createDirectoryPath = (path) => {
    path.split('/').reduce((directories, directory) => {
        directories += `${directory}/`;
        if (!fs.existsSync(directories)) {
            fs.mkdirSync(directories);
        }
        return directories;
    }, '');
};
//# sourceMappingURL=create.js.map
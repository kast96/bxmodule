const { replaceModuleName } = require("../utils")

const fs = require('fs')
const path = require('path')

export const create = (name: string) => {
  const [vendor, module] = name.split('.')
  if (!vendor || !module) {
    console.log(`Название модуля должено состоять из вендорного кода и кода модуля разделенные точкой: vendor.module`)
    return
  }

  const inputPath = 'source'
  const outputPath = `${vendor}.${module}`
  const charset = 'utf8'
  
  createDirectoryPath(path.dirname(`${outputPath}/include.php`))
  fs.writeFileSync(
    `${outputPath}/include.php`,
    replaceModuleName(fs.readFileSync(`${inputPath}/include.php`, charset), vendor, module)
  )

  console.log(`Создание модуля ${vendor}.${module} завершено`)
}

const createDirectoryPath = (path: string) => {
  path.split('/').reduce(
    (directories, directory) => {
      directories += `${directory}/`;

      if (!fs.existsSync(directories)) {
        fs.mkdirSync(directories);
      }

      return directories;
    },
    '',
  );
}
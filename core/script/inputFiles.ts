import { __dirname } from './constanst'
import { Options } from './template'

interface InputFiles {
  [key: string]: string
}

export function inputFiles(files: string[], options: Options): InputFiles {
  const { globalScript, css } = options
  const inputFiles: InputFiles = {}

  if (css) {
    inputFiles['style'] = `${__dirname}/${css.dir}/${css.filename}.${css.extension}`
  }

  if (globalScript) {
    inputFiles['global'] = `${__dirname}/${globalScript.dir}/${globalScript.filename}.${globalScript.extension}`
  }

  files.forEach(file => {
    // Replace .php with empty string in {file}
    file = file.replace('.php', '')

    // Add .php to {file}
    inputFiles[file] = `${__dirname}/${options.scripts?.dir}/${file}.${options.scripts?.extension}`
  })

  return inputFiles
}


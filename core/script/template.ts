import fs from 'fs'
import path from 'path'
import { inputFiles } from './inputFiles'
import { __dirname } from './constanst'

export interface Options {
  templates?: {
    dir: string
  },
  scripts?: {
    dir: string
    extension?: 'ts' | 'js'
  },
  css?: {
    filename?: string
    extension?: 'css' | 'scss'
    dir?: string
  },
  globalScript?: {
    filename?: string
    extension?: 'js' | 'ts'
    dir?: string
  }
}

export const defaultOptions: Options = {
  templates: {
    dir: 'templates',
  },
  scripts: {
    dir: 'src/pages',
    extension: 'ts',
  },
  css: {
    filename: 'style',
    extension: 'scss',
    dir: 'src',
  },
  globalScript: {
    filename: 'main',
    extension: 'ts',
    dir: 'src',
  }
}

// read folder dir with name 'templates'
export function readTemplatesDir(options: Options = defaultOptions) {
  const templatesDir = path.join(__dirname, options?.templates?.dir || '')
  const scriptsDir = path.join(__dirname, options.scripts?.dir || '')

  // create folder page if not exist
  if (!fs.existsSync(scriptsDir)) {
    fs.mkdirSync(scriptsDir)
  }

  // create ts files based in readTemplatesDir
  return createTsFiles(templatesDir, scriptsDir, options)
}


// create ts files based in readTemplatesDir
function createTsFiles(templatesDir: string, scriptsDir: string, options: Options) {

  // read folder dir with name 'templates'
  const files = fs.readdirSync(templatesDir)

  // create ts files based in readTemplatesDir
  files.forEach(file => {
    const filePath = path.join(templatesDir, file)
    const fileContent = fs.readFileSync(filePath, 'utf-8')

    // Validate if filePath is a .php file
    if (!file.endsWith('.php')) return

    // Replace .php with empty string in {file}
    file = file.replace('.php', '')

    // Name file with ext .ts
    const filename = path.join(scriptsDir, `${file}.${options.scripts?.extension}`)

    // Validate if fs.writeFileSync(path.join(scriptsDir, `${file}.ts`), exist
    if (fs.existsSync(filename)) return

    // create ts file
    fs.writeFileSync(filename, fileContent)
  })

  return inputFiles(files, options)
}
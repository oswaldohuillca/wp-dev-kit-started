import fs from 'fs'
import archiver from 'archiver'
import pjson from './package.json' assert { type: "json" }
import path from 'path'
import { fileURLToPath } from 'url'

const ZIP_NAME = 'generatepress_child'
const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

const output = fs.createWriteStream(`${__dirname}/${ZIP_NAME}.zip`)
const archive = archiver('zip', {
  zlib: { level: 9 } // Sets the compression level.
})

// 'close' event is fired only when a file descriptor is involved
output.on('close', function () {
  console.log(`${archive.pointer()} total bytes`)
  console.log('archiver has been finalized and the output file descriptor has closed.')
})

// It is not part of this library but rather from the NodeJS Stream API.
output.on('end', function () {
  console.log('Data has been drained')
})

// good practice to catch warnings (ie stat failures and other non-blocking errors)
archive.on('warning', function (err) {
  if (err.code === 'ENOENT') {
    // log warning
    console.warn(err)
  } else {
    // throw error
    throw err
  }
})

// good practice to catch this error explicitly
archive.on('error', function (err) {
  throw err
})

// pipe archive data to the file
archive.pipe(output)

pjson.files?.forEach((fileName) => {
  archive.glob(fileName)
})

archive.finalize()
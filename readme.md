## Description:

This project is for those who are ***WORDPRESS Developers,*** practically to create "Child Themes" of any parent Theme.


## Get Started

- Clone this repository and asign the folder name

```bash
git clone --depth 1 https://github.com/oswaldohuillca/wp-dev-kit-started.git your-theme-name
```
This command will clone the project inside folder ***your-theme-name***

- Install all NPM depencencies with npm, yarn, pnpm or bun.

```bash
pnpm install
```

- Install all PHP dependencies with composer
```bash
composer install
```

- Copy ***.env.example*** to ***.env*** file.
```bash
cp .env.example .env
```

- Run Development mode.

```bash
pnpm dev #or
yarn dev #or
bun dev #or
npm run dev
```

- Build the project

```bash
pnpm build #or
yarn build #or
bun run build #or
pnpm run build
```

- Compress the project inside .zip file, keep in mind the files that you must include within the .zip.
  You can check the file ***package.json***

```json
 "files": [
    "core/**/*",
    "dist/**/*",
    "partials/**/*",
    "templates/**/*",
    "footer.php",
    "functions.php",
    "header.php",
    "page.php",
    "style.css",
    "...otherFiles"
  ]
```

- To change the name of your compressed file ***archiver.js***. In this case the name of the zip will be  **generatepress_child.zip**

```js
const ZIP_NAME = 'generatepress_child'
const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

const output = fs.createWriteStream(`${__dirname}/${ZIP_NAME}.zip`)
const archive = archiver('zip', {
  zlib: { level: 9 } // Sets the compression level.
})
```

- Finally run **npm run zip** to generate your **WORDPRESS CHILD THEME**

```bash
pnpm zip #or
yarn zip #or
bun zip #or
npm run zip #or
```

- Upload the zip file in your page with **WORDPRESS.**

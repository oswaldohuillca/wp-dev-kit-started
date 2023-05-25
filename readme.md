## Nota:

Este proyecto es para quienes son desarradolles WORDPRESS, practicamente para crear "tema hijo" de cualquier tema padre que desea.

Cuenta con Tailwindcss, vite, sass, live reload.

## Como empezar

- Clona el repositorio y cambia el nombre de la capeta alusivo al proyecto que vas a trabajar.

```sh
git clone --depth 1 https://github.com/oswaldohuillca/wp-dev-kit-started.git your-theme-name
```
Este comando clonará el proyecto dentro de una carpeta ***your-theme-name***

- Instala todas la dependencias con npm, yarn o pnpm.

```sh
pnpm install
```

- Ejecuta el modo desarrollo

```sh
pnpm run dev
```

- Para poder crear el build ejecute.

```sh
pnpm run build
```

- Para poder comprimir en un zip, asegurate de incluir todos los archivos y carpetas que dependen tu tema.
esto  puesdes hacerlo en el archivo ***package.json***

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
    "style.css"
  ]
```
- Para cambiar el Nombre de tu archivo comprimido edita el archivo ***archiver.js***. En este caso el nombre del zip será **generatepress_child.zip**

```js
const ZIP_NAME = 'generatepress_child'
const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

const output = fs.createWriteStream(`${__dirname}/${ZIP_NAME}.zip`)
const archive = archiver('zip', {
  zlib: { level: 9 } // Sets the compression level.
})
```

- Finalmente ejecute **npm run zip** para generar su TEMA de WORDPRESS.

```sh
pnpm run zip
```
- Suba el zip a su página con WORDPRESS y listo.
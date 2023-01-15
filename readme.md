## Nota:

Este proyecto es para quienes son desarradolles WORDPRESS, practicamente para crear "tema hijo" de cualquier tema padre que desea.

Cuenta con Tailwindcss, vite, sass, live reload.

## Como empezar

- Clona el repositorio y cambia el nombre de la capeta alusivo al proyecto que vas a trabajar.

```sh
git clone https://github.com/oswaldohuillca/wp-dev-kit-started.git
```

- Instala todas la dependencias con npm.

```sh
pnpm install
```

- Genera el archivo de configuración de tailwindcss.

```sh
pnpm run tail:generate
```

- Ejecuta el modo desarrollo

```sh
pnpm run dev
```

- Finalmente genera el empaquetado.

```sh
pnpm run build
```

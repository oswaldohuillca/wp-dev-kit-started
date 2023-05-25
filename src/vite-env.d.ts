/// <reference types="vite/client" />
declare namespace credentials {

  interface Credentials {
    domain: string
    public_url: string
    security: string
    url: string
  }

  const credentials: Credentials
}

type Credentials = credentials.Credentials

const wpCredentials: Credentials = credentials.credentials
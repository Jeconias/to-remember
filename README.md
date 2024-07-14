### Resumo

Estrutura baseada na hexagonal. Essa é uma estrutura que com alguns ajustes funciona bem com NestJS ou até mesmo apenas com Express e é bem mais fácil de implementar, entretanto, não sei porque escolhi o PHP para isso (Talvez porque eu queria me atualizar com as novidades desde a versão 5).

- App ➡️ Contém clientes que utilizam os casos de uso, como por exemplo API ou CLI;
- Domain ➡️ Contém regras de negócios e não deve conhecer nada de fora do pacote;
- Infrastructure ➡️ Contém as implementações de saída dos casos de uso e outros meios de comunicações externas;

### Como rodar

- Pré-requisitos
  - PHP 8.1+
  - [Composer](https://getcomposer.org)
  - Docker

#### Na raíz do projeto execute

```sh
composer install
```

```sh
docker-compose up
```

Faça o envio para: http://127.0.0.1/finance/invoices/dispatch

#### Veja um exemplo

[![Watch the video](https://raw.githubusercontent.com/jeconias/processing/main/example/thumbnail.jpg)](https://raw.githubusercontent.com/jeconias/processing/main/example/Video.webm)

### Problemas/Pendências

- As escritas/leituras para identificar o que já foi processado está sendo em um arquivo que é salvo no disco, logo isso é lento;
- Evoluir _unit_ e _integraion_ testes

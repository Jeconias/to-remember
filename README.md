### Resumo

Essa é uma estrutura baseada na hexagonal que com alguns ajustes funciona bem com NestJS ou até mesmo apenas com Express e é bem mais fácil de implementar, entretanto, não sei porque escolhi o PHP para isso, já que não utilizo faz alguns anos (talvez porque eu queria me atualizar com as novidades desde a versão 5).

- App ➡️ Contém clientes que utilizam os casos de uso, como por exemplo API ou CLI;
- Domain ➡️ Contém regras de negócios e não deve conhecer nada de fora do pacote;
- Infrastructure ➡️ Contém as implementações de saída dos casos de uso e outros meios de comunicações externas;

### Como rodar

- Pré-requisitos
  - Docker

#### Na raíz do projeto execute

```sh
docker-compose up
```

Faça o envio para do [arquivo](./example/more.csv) para: http://127.0.0.1/finance/invoices/dispatch

#### Veja um exemplo

[Vídeo.webm](https://github.com/user-attachments/assets/b338e566-c911-456e-96b2-be253562b5c0)

[Raw video here](./example/Vídeo.webm)

### Problemas/Pendências

- As escritas/leituras para identificar o que já foi processado está sendo em um arquivo, logo, se torna mais lento comparado ao uso da memória ou a algum serviço dedicado para isso;
- Evoluir _unit_ e _integraion_ testes;
- Tratamento de exceções em alguns pontos.

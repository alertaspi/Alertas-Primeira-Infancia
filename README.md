# prontuariocidadao-appalertas

Requisitos:
- PHP instalado ou algum software de desenvolvimento PHP como o Wamp, Xamp ou Vertrigo
- Composer instalado

Para funcionar basta:
1) Clonar o repositório
2) Ir até os arquivos locais utilizando algum terminal e executar o comando "composer install" para instalar as dependências
3) Colocar dentro de algum servidor PHP (Wamp, Xamp, Vertrigo)
4) Ir no navegador e acessar a URL local
5) Baixar o Pentaho Data Integration - PDI https://sourceforge.net/projects/pentaho/files/
5.1) Descompactar qualquer versão do PDI na raiz do projeto
5.2) Ajustar as linhas dos comandos shell_exec nas classes ContainerWindowView e FormImportEventosView, para que aponte para a sua parta PDI dentro do projeto 

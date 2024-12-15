# Aletheia

Nosso projeto consiste na elaboração e desenvolvimento de um Sistema Web com persistência de dados e autenticação de usuários.

# Executando o Projeto

Requisitos:
- WampServer (ou outro servidor virtual);
- VS Code (ou outro ambiente que permita a edição de códigos);
- MySQL Workbench.

Acessando o site localmente:
Os arquivos .html .css & .php, após baixados, devem ser realocados para uma mesma pasta. Em especial, o arquivo .css deve ser colocado dentro de um subdiretório nomeado "css". 
A pasta contendo todos esses arquivos deve ser realocada para a pasta www, disponível após a instalação do WampServer em C:\wamp64\www (ou o diretório de instalação que você escolheu);
<br><br>
O Banco de Dados aletheia_db pode ser importado pelo MySQL Workbench após estabelecida a conexão com o Wamp Server(servidor local) da seguinte maneira: Instância Local wampmysql>Administração>Importar Dados>Importar de um arquivo independente>aletheia_db.sql>Começar Importação
<br>
Após a importação do banco, você deve alterar a senha no arquivo configA.php, dentro da pasta criada anteriormente. O campo que deve ser alterado se parece com isso: $_DB['password'] = '(insira a senha MySQL aqui)'; // Senha MySQL
<br>
<br>
O Sistema pode ser acessado através do seu navegador através do caminho: http://localhost/(nome_da_sua_pasta)/index.html -> caso contrário, não será possível utilizar as funções do php (essenciais para executar as funcionalidades do sistema).
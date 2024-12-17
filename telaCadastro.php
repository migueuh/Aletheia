<?php
// Adicionando a importação das configurações antes do codigo;
require_once("configA.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Consulta para verificar se o e-mail já existe
    $sql = "SELECT COUNT(*) AS count FROM aluno WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    
    // Retorna uma resposta JSON
    if ($count > 0) {
        echo json_encode(['exists' => true]);  // E-mail já cadastrado
        exit();
    } 
  } 

  $nome = $_POST["nome"] ?? 'Anônimo';
  $email = ($_POST["email"]);
  $cpf = $_POST["cpf"];
  $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    
    // ERROR CORREÇÃO
    // Adicionada verificação, caso não de erro ele redireciona para a pagina inicial;
  try {
    $consulta = $mysqli->prepare("INSERT INTO aluno (nome, email, cpf, senha) VALUES (?, ?, ?, ?)");
    $consulta->bind_param("ssss",$_POST['nome'], $_POST['email'], $_POST['cpf'], $senha );
    $consulta->execute();
    $consulta->close();
    header('Location: telaEntrar.php');
    echo "
      <script>alert('Cadastro realizado com sucesso!')</script>
      ";
  } catch (\Throwable $th) {
    throw $th;
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf8mb4" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #e9f0f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    
    .fundo {
      background-color: #f2f2f2;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
      font-size: 24px;
      font-weight: 600;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 8px;
      font-size: 14px;
      color: #555;
    }

    input,
    select {
      margin-bottom: 0px;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 14px;
      transition: border-color 0.3s ease;
    }

    button {
      padding: 12px;
      background-color: #4caf50;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #45a049;
    }

    button:active {
      background-color: #388e3c;
    }

    .aviso {
      font-size: 12px;
      color: #777;
      margin-top: 5px;
      text-align: left;
      display: block;
    }

    #alerta, #emailError {
        display: block;
        margin-top: 1px;
        font-size: 14px;
        color: red;
    }
    
  </style>
</head>

<body>

  <div class="fundo">
    <div class="container">
    <h1>Cadastro</h1>
    <form action="telaCadastro.php" method="POST">
        <label for="nome">Insira seu nome:</label>
        <input type="text" id="nome" maxlength="60" name="nome" >
        <span class="aviso" style=display:none>Não obrigatório</span><br>

        <label for="email">Insira seu Email:</label>
        <input type="email" id="email" name="email" maxlength="64">
        <span id="emailError" style="color: red;"></span> <!-- Exibe mensagem de erro --></br>

        <label for="cpf">Insira seu cpf:</label>
        <input type="text" placeholder="XXX.XXX.XXX-XX" id="cpf" name="cpf" maxlength="14" oninput="this.value = this.value.replace(/[^0-9.-]/g, '')" required>
        <span class="aviso" id="alerta"></span><br>

        <label for="senha">Crie uma senha:</label>
        <input type="password" id="senha" name="senha" required><br>
       
        <button type="submit">Cadastrar</button>
    </form>

    <p class="aviso">Já tem uma conta? <a href="telaEntrar.php">Faça login aqui</a>.</p>

    </div>
  </div>

  <!-- SCRIPT (JS)-->
  <script>
      const input = document.querySelector('#cpf')
      const aviso = document.querySelector('#alerta');

      input.addEventListener('keypress', () => {
        let inputlength = input.value.length

        if (inputlength === 3 || inputlength === 7) {
            input.value += '.'
        }else if (inputlength === 11){
          input.value += '-'
        }
      })

      input.addEventListener('blur', () => {
      // Remove qualquer formatação antes de validar
      let cpf = input.value.replace(/[^\d]/g, '');

      if (validarCPF(cpf)) {
          aviso.textContent = ''; // Limpa a mensagem de erro
          aviso.style.color = 'green'; // Alerta de sucesso
      } else {
          aviso.textContent = 'CPF inválido!';
          aviso.style.color = 'red'; // Alerta de erro
        }
      });

      // Função para validar o CPF
      function validarCPF(cpf) {
          // Verifica se o CPF tem 11 dígitos
          if (cpf.length !== 11 || /^\d+$/.test(cpf) === false) {
              return false;
          }

          // Valida se o CPF não é um dos CPFs inválidos comuns (111.111.111-11, 222.222.222-22, etc.)
          if (/^(\d)\1{10}$/.test(cpf)) {
              return false;
          }

          // Valida o primeiro dígito verificador
          let soma = 0;
          let multiplicador = 10;
          for (let i = 0; i < 9; i++) {
              soma += parseInt(cpf.charAt(i)) * multiplicador--;
          }

          let resto = soma % 11;
          let digito1 = resto < 2 ? 0 : 11 - resto;

          // Valida o segundo dígito verificador
          soma = 0;
          multiplicador = 11;
          for (let i = 0; i < 10; i++) {
              soma += parseInt(cpf.charAt(i)) * multiplicador--;
          }

          resto = soma % 11;
          let digito2 = resto < 2 ? 0 : 11 - resto;

          // Verifica se os dígitos calculados coincidem com os do CPF
          if (digito1 === parseInt(cpf.charAt(9)) && digito2 === parseInt(cpf.charAt(10))) {
              return true;
          } else {
              return false;
          }
      }

      document.querySelector('#email').addEventListener('blur', function() {
        var email = document.querySelector('#email').value;
        var emailError = document.querySelector('#emailError');

        if (email !== '') {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'telaCadastro.php', true);  // A mesma página do cadastro
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    
                    if (response.exists) {
                        emailError.textContent = 'Este e-mail já está cadastrado.';
                    } else {
                        emailError.textContent = '';  // Limpa a mensagem de erro
                    }
                }
            };

            xhr.send('email=' + encodeURIComponent(email));  // Envia apenas o e-mail para verificação
          }
      });



  </script>
</body>

</html>
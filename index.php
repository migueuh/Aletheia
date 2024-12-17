<?php
include("configA.php");

// ERROR CORREÇÃO
// Adicionando pré carregamento para trazer a listagem dos comentarios no banco;
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['email'])) {
   
  // consulta todos os comentarios da table em ordem decrescente
  $sql = "SELECT * FROM comentario ORDER BY id DESC";
  $query = $mysqli->query($sql) or die("Falha na execução do código SQL: " . $mysqli->error);

  $comentarios = $query->fetch_all(MYSQLI_ASSOC);
} else if (isset($_SESSION['admin_email'])) {
  $sql = "SELECT * FROM comentario ORDER BY id DESC";
  $query = $mysqli->query($sql) or die("Falha na execução do código SQL: " . $mysqli->error);

  $comentarios = $query->fetch_all(MYSQLI_ASSOC);
} else {
  $comentarios = [];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Aletheia</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet" />
        <!-- Incluindo jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />

        <style>

        .comment-section {
          width: 100%;
          max-width: 800px;
          margin: 20px auto;
          padding: 15px;
          background-color: #f9f9f9;
          border: 1px solid #ddd;
          border-radius: 8px;
          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comment {
          position: relative;
          border-bottom: 1px solid #ccc;
          padding: 10px 0;
          margin-bottom: 10px;
        }

        .delete-comment-btn {
          display: none;
          position: absolute;
          top: 5px;
          right: 5px;
          background-color: transparent;
          border: none;
          color: #ff0000;
          font-size: 14px;
          font-weight: bold;
          cursor: pointer;
          padding: 0;
        }

        .comment:last-child {
          border-bottom: none;
          margin-bottom: 0;
        }

        .comment-title {
          font-size: 18px;
          font-weight: bold;
          color: #333;
          margin-bottom: 5px;
        }

        .comment-text {
          font-size: 16px;
          color: #555;
          line-height: 1.5;
          margin-bottom: 5px;
        }

        .comment-author {
          font-size: 14px;
          font-weight: bold;
          color: #777;
          text-align: left;
          margin-top: 5px;
        }

        .comment-footer {
          display: flex;
          justify-content: space-between;
          align-items: center; /* Alinha verticalmente no centro */
          font-size: 12px;
          color: #777;
        }

        small{
          font-style: italic;
          font-size: 12px;
          color: #777;
        }
        </style>

    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
            <div class="container px-5">
                <a class="navbar-brand" href="#page-top">Aletheia</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a id="link1" class="nav-link" href="telaCadastro.php">Cadastrar-se</a></li>
                        <li class="nav-item"><a id="link2" class="nav-link" href="telaEntrar.php">Entrar</a></li>
                        <li class="nav-item"><a id="link3" style="display: none;" class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="masthead text-center text-white">
            <div class="masthead-content">
                <div class="container px-5">
                    <h1 class="masthead-heading mb-0">Onde a verdade</h1>
                    <h2 class="masthead-subheading mb-0">Encontra a voz</h2>
                    <a class="btn btn-primary btn-xl rounded-pill mt-5" id="btnComentario" onclick="verificarSessao()">Fazer comentário</a> <!-- FAZER VERIFICAÇÃO SE O USUÁRIO JA EXISTE NO BD. SE NÃO EXISTIR ELE VAI PRA TELA DE SE CADASTRAR. SE EXISTIR ELE PODE COMENTAR-->
                </div>
            </div>
        </header>

        <section>
            <div class="container px-5">
                <section style="background-color: white;">
                    <div class="container my-5 py-5">
                      <div class="row d-flex justify-content-center">
                        <div class="comment-section" id="commentSection">
                            <!-- ERROR CORREÇÃO -->
                            <!-- LISTANDO NA TELA TODOS OS COMENTARIOS -->
                            <?php
                              foreach ($comentarios as $key => $value) {
                                # code...
                                $autor = $value["aluno_email"];
                                $texto = $value["texto"];
                                $data = $value["data"];
                                $titulo = $value["titulo"];
                                $id = $value["id"];

                                echo "
                                    <div class='comment'>
                                      <button class='delete-comment-btn' id='btnDeletar' data-id='$id'> X </button>
                                      <div class='comment-title'> $titulo </div>
                                      <div class='comment-text'> $texto </div>
                                      <div class='comment-author'>Autor: $autor</div>
                                      <small>$data</small>
                                    </div>
                                  ";
                                }
                            ?>
                        </div>
                      </div>
                    </div>
                  </section>
            </div>
        </section>
        
        <!-- Footer-->
        <footer class="py-5 bg-black">
            <div class="container px-5"><p class="m-0 text-center text-white small">Copyright &copy; ALETHEIA 2024</p></div>
        </footer>
        <div class="modal" id="commentModal">
          <div class="modal-content">
            <h4>Adicionar Comentário</h4>
            <div class="comment-area">
              <textarea id="commentTitle" class="form-control" placeholder="Título do comentário" rows="1"></textarea><br>
              <textarea id="commentText" class="form-control" placeholder="Faça seu comentário aqui" rows="4"></textarea>
            </div>
            <div class="comment-btns">
              <button class="btn btn-danger" onclick="closeModal()">Cancelar</button>
              <button onclick="addComment()" class="btn btn-success">Enviar</button>
            </div>
          </div>
        </div>
        
        <!-- SCRIPT (JS)-->
        <script>
            function verificarSessao() {
            fetch('verificarSessao.php')
                .then(response => response.json())
                .then(data => {
                    if (data.logado) {
                        openModal();
                    } else {
                        alert("Você precisa entrar ou criar uma conta para comentar!");
                        document.getElementById("commentModal").style.display = "none";
                    }
                })}

                
                function verificarSessao2() {
                  fetch('verificarSessao.php')
                  .then(response => response.json())
                  .then(data => {
                      if (data.logado) {
                        document.getElementById("link1").style.display = "none";
                        document.getElementById("link2").style.display = "none";
                        document.getElementById("link3").style.display = "inline";
                      } else {
                        document.getElementById("link1").style.display = "inline";
                        document.getElementById("link2").style.display = "inline";
                        document.getElementById("link3").style.display = "none";
                      }
                  })
                }
                verificarSessao2();

                function verificarUser3(){
                  fetch('verificarSessao.php')
                  .then(response => response.json())
                  .then(data => {
                      if (data.tipo === 'admin') {
                        console.log(data.tipo)
                        const deleteButtons = document.querySelectorAll('.delete-comment-btn');
                        deleteButtons.forEach(button => {
                            button.style.display = 'inline';  // Exibe o botão
                        });
                        document.getElementById("btnComentario").style.display = 'none';
                      } 
                  })
                }

                verificarUser3()

             function openModal() {
              document.getElementById("commentModal").style.display = "flex";
            } 
        
            function closeModal() {
              document.getElementById("commentModal").style.display = "none";
              document.getElementById("commentTitle").value = "";
              document.getElementById("commentText").value = "";
            }

            function addComment() {
              console.log("Iniciando envio de comentário...");  

              var titulo = $('#commentTitle').val();
              var texto = $('#commentText').val();

              //Enviando o texto e o titulo digitado pelo usuario
              var commentData = {
                title: titulo,
                text: texto
              };

              console.log(titulo);  
              console.log(texto);
              
              if (titulo.trim() === "" || texto.trim() === "") {
                alert("Título e comentário não podem estar vazios.");
                return;
              }

              // Usando $.ajax para enviar os dados ao servidor
              $.ajax({
                url: "salvarcomentario.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(commentData),
                success: function(response) {
                  console.log("Resposta do servidor:", response);
                    try {
                     // Tente analisar como JSON
                      console.log(response);
                      if(response.status == "success"){location.reload()}
                    } catch (e) {
                      console.error("Erro ao analisar JSON:", e);
                    }
                  },
                  error: function(xhr, status, error) {
                    console.log()
                    console.log("Erro AJAX:", error);
                  }
              });
            }

            document.addEventListener("click", function (event) {
            // Verifica se o clique foi no botão "X"
            if (event.target.classList.contains("delete-comment-btn")) {
              // Pega o ID do comentário a partir do atributo "data-id"
              const commentId = event.target.getAttribute('data-id');

              // Confirmação antes de excluir
              if (confirm("Tem certeza de que deseja excluir este comentário?")) {
                // Envia a requisição AJAX para excluir o comentário
                fetch("excluirComentario.php", {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/json",
                  },
                  body: JSON.stringify({ id: commentId }), // Envia o ID do comentário
                })
                  .then((response) => response.json())
                  .then((data) => {
                    if (data.status === "success") {
                      alert("Comentário excluído com sucesso!");
                      // Remove o comentário da interface
                      event.target.closest(".comment").remove();
                    } else {
                      alert("Erro ao excluir comentário: " + data.message);
                    }
                  })
                  .catch((error) => {
                    console.error("Erro ao excluir comentário:", error);
                    alert("Erro ao excluir comentário. Tente novamente.");
                  });
              }
            }
          });



          </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> 
    </body>
</html>

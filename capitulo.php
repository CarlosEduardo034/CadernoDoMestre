<?php
session_start();
include("Config/Config.php");

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Capítulo inválido";
    exit;
}

$sql = "SELECT * FROM capitulos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['id']);
$stmt->execute();   

$result = $stmt->get_result();
$capitulo = $result->fetch_assoc();

if (!$capitulo) {
    echo "Capítulo não encontrado";
    exit;
}
?>

<h1><?= $capitulo['nome'] ?></h1>
<p><?= $capitulo['descricao'] ?></p>

<h2>Criar página</h2>

<input type="text" id="tituloPagina" placeholder="Título da página">
<button onclick="criarPagina()">Criar</button>

<h2>Páginas</h2>
<div id="listaPaginas"></div>


<a href="lixeira.php">Lixeira</a>
<a href="Dashboard/Dashboard.php">Voltar ao Dashboard</a>

<script>
    const capituloId = <?= $capitulo['id'] ?>;

    function criarPagina() {
        const titulo = document.getElementById("tituloPagina").value;

        fetch("criar_pagina.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `titulo=${encodeURIComponent(titulo)}&capitulo_id=${capituloId}`
        })
        .then(res => res.text())
        .then(data => {
            if (data === "ok") {
                carregarPaginas();
            } else {
                alert("Erro: " + data);
            }
        });
    }

    function carregarPaginas() {
        fetch(`listar_paginas.php?capitulo_id=${capituloId}`)
        .then(res => res.json())
        .then(paginas => {
            const container = document.getElementById("listaPaginas");
            container.innerHTML = "";

            paginas.forEach(p => {
                const div = document.createElement("div");

                div.style.border = "1px solid #ccc";
                div.style.padding = "10px";
                div.style.marginBottom = "10px";

                div.innerHTML = `
                    <div id="view-p-${p.id}">
                        <h3>${p.titulo}</h3>

                        <div 
                            contenteditable="true"
                            id="conteudo-${p.id}"
                            style="min-height: 80px; border:1px solid #aaa; padding:5px;"
                        >
                            ${p.conteudo || ""}
                        </div>
                        <small id="status-${p.id}"></small>

                        <button onclick="editarPagina(${p.id}, '${p.titulo}')">
                            Editar
                        </button>

                        <button onclick="excluirPagina(${p.id})" style="color:red;">
                            Excluir
                        </button>
                    </div>

                    <div id="edit-p-${p.id}" style="display:none;">
                        <input type="text" id="titulo-p-${p.id}" value="${p.titulo}">
                        <button onclick="salvarPagina(${p.id})">Salvar</button>
                    </div>
                `;

                container.appendChild(div);
                configurarAutoSave(p.id);
            });
        });
    }

    carregarPaginas();

    function editarPagina(id, titulo) {
        document.getElementById(`view-p-${id}`).style.display = "none";
        document.getElementById(`edit-p-${id}`).style.display = "block";
    }

    function salvarPagina(id) {
        const titulo = document.getElementById(`titulo-p-${id}`).value;

        fetch("editar_pagina.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id=${id}&titulo=${encodeURIComponent(titulo)}`
        })
        .then(res => res.text())
        .then(data => {
            if (data === "ok") {
                carregarPaginas(); // recarrega lista
            } else {
                alert("Erro: " + data);
            }
        });
    }

    function excluirPagina(id) {
        if (!confirm("Mover página para lixeira?")) return;

        fetch("excluir_pagina.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id=${id}`
        })
        .then(res => res.text())
        .then(data => {
            if (data === "ok") {
                carregarPaginas();
            } else {
                alert("Erro: " + data);
            }
        });
    }

    function configurarAutoSave(id) {
        const el = document.getElementById(`conteudo-${id}`);
        const status = document.getElementById(`status-${id}`);

        let timeout = null;

        el.addEventListener("input", () => {
            status.innerText = "Salvando...";

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                const conteudo = el.innerHTML;

                console.log("Enviando:", id, conteudo);

                fetch("salvar_conteudo.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `id=${id}&conteudo=${encodeURIComponent(conteudo)}`
                })
                .then(res => res.text())
                .then(data => {
                    if (data === "ok") {
                        status.innerText = "Salvo ✔";
                    } else {
                        status.innerText = "Erro ao salvar";
                    }
                });
            }, 800); // tempo de espera (ms)
        });
    }
</script>
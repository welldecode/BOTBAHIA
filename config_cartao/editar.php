<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.configcartao.php';

$cartao = new ConfigCartao();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($_POST["nome_completo"] != "" && $_POST["cpf"] != "" && $_POST["card_number"] != "" && $_POST["validade"] != "" && $_POST["cvv"] != "") {
    $nomeCompleto = $_POST["nome_completo"];
    $cpf = $_POST["cpf"];
    $cardNumber = $_POST["card_number"];
    $numeroCartao = password_hash($cardNumber, PASSWORD_DEFAULT);
    $validade = $_POST["validade"];
    $cvv = $_POST["cvv"];

    $cartao->updateConfig($nomeCompleto, $cpf, $numeroCartao, $validade, $cvv);

    $_SESSION['mensagem'] = "A configuração do cartão foi salva com sucesso.";
    $_SESSION['mensagem_tipo'] = "success";
    header("Location: index.php");
    exit();
  } else {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    $_SESSION['mensagem_tipo'] = "erro";
    header("Location: editar.php");
    exit();
  }
}

$stmt = $cartao->getConfig();
$configCartao = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/content.css">
  <link rel="stylesheet" href="/assets/css/effect-fade.css">
  <link rel="stylesheet" href="/assets/css/swiper.min.css">
  <link rel="stylesheet" href="/assets/css/app-dollar.css">
  <link rel="stylesheet" href="/assets/css/app-tournaments.css">
  <link rel="stylesheet" href="/assets/css/background.css">
  <link rel="stylesheet" href="/assets/css/botaoCollapsado.css">
  <link rel="stylesheet" href="/assets/css/botaoPadrao.css">
  <link rel="stylesheet" href="/assets/css/cup-violet.css">
  <link rel="stylesheet" href="/assets/css/cartao.css">
  <link rel="stylesheet" href="/assets/css/tabela.css">
  <style>
    .name {
      text-transform: uppercase;
    }
  </style>
</head>

<body>
  <?php include '/var/www/html/includes/alert.php'; ?>

  <div class="authenticated-layout">
    <?php include '/var/www/html/includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="app-background -top-position -dark"><img src="/assets/default.060da614.jpg" alt=""></div>
        <div style="display: flex; margin-top: -46px; gap: 30px">
          <form class="card-panel" action="editar.php" method="POST" style="width: 400px;">
            <div
              style="margin-bottom: 20px;border-bottom: 1px dashed gray;padding-bottom: 16px;display: flex;align-items: center;gap: 7px;">
              <a style="cursor: pointer;" onclick="window.history.back()"><img src="/assets/left-arrow.svg"
                  alt=""></a>EDITAR CARTÃO
            </div>
            <div class="form-group">
              <label for="nome_completo">Nome Completo</label>
              <input type="text" name="nome_completo" id="nome_completo" value="<?= $configCartao['nome_completo'] ?>">
              <span>Nome exibido no cartão</span>
            </div>
            <div class="form-group">
              <label for="cpf">CPF</label>
              <input type="text" name="cpf" id="cpf" value="<?= $configCartao['cpf'] ?>">
              <span>Insira seu CPF</span>
            </div>
            <div class="form-group">
              <label for="card_number">Número do cartão</label>
              <input type="text" name="card_number" id="card_number">
              <span>Número exibido no cartão</span>
            </div>
            <div class="form-group">
              <label for="validade">Validade</label>
              <input type="text" name="validade" id="validade" placeholder="mm/aa"
                value="<?= $configCartao['vencimento'] ?>">
              <span>Insira a validade do cartão</span>
            </div>
            <div class="form-group">
              <label for="cvv">Código de segurança</label>
              <input type="password" name="cvv" id="cvv" value="<?= $configCartao['cvv'] ?>">
              <span>Código de 3 dígitos no verso do cartão</span>
            </div>
            <button type="submit" class="btn-primary">Salvar cartão</button>
          </form>

          <div class="flip-card" style="margin-top: 40px;">
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <p class="heading_8264">MASTERCARD</p>
                <svg class="logo" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="60" height="60"
                  viewBox="0 0 48 48">
                  <path fill="#ff9800" d="M32 10A14 14 0 1 0 32 38A14 14 0 1 0 32 10Z"></path>
                  <path fill="#d50000" d="M16 10A14 14 0 1 0 16 38A14 14 0 1 0 16 10Z"></path>
                  <path fill="#ff3d00"
                    d="M18,24c0,4.755,2.376,8.95,6,11.48c3.624-2.53,6-6.725,6-11.48s-2.376-8.95-6-11.48 C20.376,15.05,18,19.245,18,24z">
                  </path>
                </svg>
                <svg version="1.1" class="chip" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px"
                  viewBox="0 0 50 50" xml:space="preserve">
                  <image id="image0" width="50" height="50" x="0" y="0" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAMAAAAp4XiDAAAABGdBTUEAALGPC/xhBQAAACBjSFJN
              AAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAB6VBMVEUAAACNcTiVeUKVeUOY
              fEaafEeUeUSYfEWZfEaykleyklaXe0SWekSZZjOYfEWYe0WXfUWXe0WcgEicfkiXe0SVekSXekSW
              ekKYe0a9nF67m12ZfUWUeEaXfESVekOdgEmVeUWWekSniU+VeUKVeUOrjFKYfEWliE6WeESZe0GS
              e0WYfES7ml2Xe0WXeESUeEOWfEWcf0eWfESXe0SXfEWYekSVeUKXfEWxklawkVaZfEWWekOUekOW
              ekSYfESZe0eXekWYfEWZe0WZe0eVeUSWeETAnmDCoWLJpmbxy4P1zoXwyoLIpWbjvXjivnjgu3bf
              u3beunWvkFWxkle/nmDivXiWekTnwXvkwHrCoWOuj1SXe0TEo2TDo2PlwHratnKZfEbQrWvPrWua
              fUfbt3PJp2agg0v0zYX0zYSfgkvKp2frxX7mwHrlv3rsxn/yzIPgvHfduXWXe0XuyIDzzISsjVO1
              lVm0lFitjVPzzIPqxX7duna0lVncuHTLqGjvyIHeuXXxyYGZfUayk1iyk1e2lln1zYTEomO2llrb
              tnOafkjFpGSbfkfZtXLhvHfkv3nqxH3mwXujhU3KqWizlFilh06khk2fgkqsjlPHpWXJp2erjVOh
              g0yWe0SliE+XekShhEvAn2D///+gx8TWAAAARnRSTlMACVCTtsRl7Pv7+vxkBab7pZv5+ZlL/UnU
              /f3SJCVe+Fx39naA9/75XSMh0/3SSkia+pil/KRj7Pr662JPkrbP7OLQ0JFOijI1MwAAAAFiS0dE
              orDd34wAAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQfnAg0IDx2lsiuJAAACLElEQVRIx2Ng
              GAXkAUYmZhZWPICFmYkRVQcbOwenmzse4MbFzc6DpIGXj8PD04sA8PbhF+CFaxEU8iWkAQT8hEVg
              OkTF/InR4eUVICYO1SIhCRMLDAoKDvFDVhUaEhwUFAjjSUlDdMiEhcOEItzdI6OiYxA6YqODIt3d
              I2DcuDBZsBY5eVTr4xMSYcyk5BRUOXkFsBZFJTQnp6alQxgZmVloUkrKYC0qqmji2WE5EEZuWB6a
              lKoKdi35YQUQRkFYPpFaCouKIYzi6EDitJSUlsGY5RWVRGjJLyxNy4ZxqtIqqvOxaVELQwZFZdkI
              JVU1RSiSalAt6rUwUBdWG1CP6pT6gNqwOrgCdQyHNYR5YQFhDXj8MiK1IAeyN6aORiyBjByVTc0F
              qBoKWpqwRCVSgilOaY2OaUPw29qjOzqLvTAchpos47u6EZyYnngUSRwpuTe6D+6qaFQdOPNLRzOM
              1dzhRZyW+CZouHk3dWLXglFcFIflQhj9YWjJGlZcaKAVSvjyPrRQ0oQVKDAQHlYFYUwIm4gqExGm
              BSkutaVQJeomwViTJqPK6OhCy2Q9sQBk8cY0DxjTJw0lAQWK6cOKfgNhpKK7ZMpUeF3jPa28BCET
              amiEqJKM+X1gxvWXpoUjVIVPnwErw71nmpgiqiQGBjNzbgs3j1nus+fMndc+Cwm0T52/oNR9lsdC
              S24ra7Tq1cbWjpXV3sHRCb1idXZ0sGdltXNxRateRwHRAACYHutzk/2I5QAAACV0RVh0ZGF0ZTpj
              cmVhdGUAMjAyMy0wMi0xM1QwODoxNToyOSswMDowMEUnN7UAAAAldEVYdGRhdGU6bW9kaWZ5ADIw
              MjMtMDItMTNUMDg6MTU6MjkrMDA6MDA0eo8JAAAAKHRFWHRkYXRlOnRpbWVzdGFtcAAyMDIzLTAy
              LTEzVDA4OjE1OjI5KzAwOjAwY2+u1gAAAABJRU5ErkJggg=="></image>
                </svg>
                <svg version="1.1" class="contactless" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px"
                  viewBox="0 0 50 50" xml:space="preserve">
                  <image id="image0" width="50" height="50" x="0" y="0" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAQAAAC0NkA6AAAABGdBTUEAALGPC/xhBQAAACBjSFJN
              AAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAJcEhZ
              cwAACxMAAAsTAQCanBgAAAAHdElNRQfnAg0IEzgIwaKTAAADDklEQVRYw+1XS0iUURQ+f5qPyjQf
              lGRFEEFK76koKGxRbWyVVLSOgsCgwjZBJJYuKogSIoOonUK4q3U0WVBWFPZYiIE6kuArG3VGzK/F
              fPeMM/MLt99/NuHdfPd888/57jn3nvsQWWj/VcMlvMMd5KRTogqx9iCdIjUUmcGR9ImUYowyP3xN
              GQJoRLVaZ2DaZf8kyjEJALhI28ELioyiwC+Rc3QZwRYyO/DH51hQgWm6DMIh10KmD4u9O16K49it
              VoPOAmcGAWWOepXIRScAoJZ2Frro8oN+EyTT6lWkkg6msZfMSR35QTJmjU0g15tIGSJ08ZZMJkJk
              HpNZgSkyXosS13TkJpZ62mPIJvOSzC1bp8vRhhCakEk7G9/o4gmZdbpsTcKu0m63FbnBP9Qrc15z
              bkbemfgNDtEOI8NO5L5O9VYyRYgmJayZ9nPaxZrSjW4+F6Uw9yQqIiIZwhp2huQTf6OIvCZyGM6g
              DJBZbyXifJXr7FZjGXsdxADxI7HUJFB6iWvsIhFpkoiIiGTJfjJfiCuJg2ZEspq9EHGVpYgzKqwJ
              qSAOEwuJQ/pxPvE3cYltJCLdxBLiSKKIE5HxJKcTRNeadxfhDiuYw44zVs1dxKwRk/uCxIiQkxKB
              sSctRVAge9g1E15EHE6yRUaJecRxcWlukdRIbGFOSZCMWQA/iWauIP3slREHXPyliqBcrrD71Amz
              Z+rD1Mt2Yr8TZc/UR4/YtFnbijnHi3UrN9vKQ9rPaJf867ZiaqDB+czeKYmd3pNa6fuI75MiC0uX
              XSR5aEMf7s7a6r/PudVXkjFb/SsrCRfROk0Fx6+H1i9kkTGn/E1vEmt1m089fh+RKdQ5O+xNJPUi
              cUIjO0Dm7HwvErEr0YxeibL1StSh37STafE4I7zcBdRq1DiOkdmlTJVnkQTBTS7X1FYyvfO4piaI
              nKbDCDaT2anLudYXCRFsQBgAcIF2/Okwgvz5+Z4tsw118dzruvIvjhTB+HOuWy8UvovEH6beitBK
              xDyxm9MmISKCWrzB7bSlaqGlsf0FC0gMjzTg6GgAAAAldEVYdGRhdGU6Y3JlYXRlADIwMjMtMDIt
              MTNUMDg6MTk6NTYrMDA6MDCjlq7LAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDIzLTAyLTEzVDA4OjE5
              OjU2KzAwOjAw0ssWdwAAACh0RVh0ZGF0ZTp0aW1lc3RhbXAAMjAyMy0wMi0xM1QwODoxOTo1Nisw
              MDowMIXeN6gAAAAASUVORK5CYII="></image>
                </svg>
                <p class="number">9759 2484 5269 6576</p>
                <p class="valid_thru">VALIDADE</p>
                <p class="date_8264">0 4 / 2 4</p>
                <p class="name"><?= $configCartao['nome_completo'] ?></p>
              </div>
              <div class="flip-card-back">
                <div class="strip"></div>
                <div class="mstrip"></div>
                <div class="sstrip">
                  <p class="code">***</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.getElementById('card_number').addEventListener('input', function () {
      var input = this.value.replace(/\D/g, '').slice(0, 16);
      var formatted = '';
      for (var i = 0; i < input.length; i++) {
        if (i > 0 && i % 4 === 0) {
          formatted += ' '; // Adiciona um espaço a cada 4 números
        }
        formatted += input[i];
      }
      document.querySelector('.number').textContent = formatted;
    });

    document.getElementById('nome_completo').addEventListener('input', function () {
      var input = this.value.slice(0, 30);
      var formatted = '';
      for (var i = 0; i < input.length; i++) {
        formatted += input[i];
      }
      document.querySelector('.name').textContent = formatted;
    });

    document.getElementById('validade').addEventListener('input', function () {
      var input = this.value.replace(/[^\d/]/g, '').slice(0, 5);
      var formatted = '';
      for (var i = 0; i < input.length; i++) {
        if (i > 0 && i % 1 === 0) {
          formatted += ' '; // Adiciona um espaço a cada 1 números
        }
        formatted += input[i];
      }
      document.querySelector('.date_8264').textContent = formatted;
    });

    document.getElementById('validade').addEventListener('input', function () {
      var input = this.value.replace(/[^\d]/g, ''); // Remove caracteres não numéricos
      var formatted = '';
      // Limita a entrada a 4 caracteres: 2 para o mês e 2 para o ano
      input = input.slice(0, 4);
      for (var i = 0; i < input.length; i++) {
        // Insere uma barra após o mês (dois primeiros dígitos)
        if (i === 2) {
          formatted += '/';
        }
        formatted += input[i];
      }
      this.value = formatted; // Atualiza o valor do campo com a formatação
    });

    document.getElementById('card_number').addEventListener('input', function () {
      this.value = this.value.slice(0, 16); // Limita o número de caracteres a 16
    });

    document.getElementById('cvv').addEventListener('input', function () {
      this.value = this.value.slice(0, 3); // Limita o número de caracteres a 3
    });

    botaoSubmit = document.querySelector(".btn-primary");
    document.getElementById('cpf').addEventListener('input', function () {
      var input = this.value.replace(/[^\d]/g, '').slice(0, 11); // Aceita apenas números e limita a 11 dígitos
      var formatted = '';
      for (var i = 0; i < input.length; i++) {
        if (i === 3 || i === 6) {
          formatted += '.'; // Adiciona um ponto após o terceiro e sexto dígito
        } else if (i === 9) {
          formatted += '-'; // Adiciona um traço após o nono dígito
        }
        formatted += input[i];
      }
      this.value = formatted; // Atualiza o valor do campo com a formatação

      if (input.length === 11 && validarCPF(input)) {
        this.style.borderColor = 'green'; // Cor da borda verde se o CPF for válido
        this.style.borderWidth = '2px';
        botaoSubmit.disabled = false;
      } else {
        this.style.borderColor = 'red'; // Cor da borda vermelha se o CPF for inválido
        this.style.borderWidth = '2px';
        botaoSubmit.disabled = true;
      }
    });

    function validarCPF(cpf) {
      var soma = 0;
      var resto;

      if (cpf === "00000000000") return false;

      for (var i = 1; i <= 9; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
      }
      resto = (soma * 10) % 11;

      if ((resto === 10) || (resto === 11)) resto = 0;
      if (resto !== parseInt(cpf.substring(9, 10))) return false;

      soma = 0;
      for (var i = 1; i <= 10; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
      }
      resto = (soma * 10) % 11;

      if ((resto === 10) || (resto === 11)) resto = 0;
      if (resto !== parseInt(cpf.substring(10, 11))) return false;

      return true;
    }
  </script>
</body>

</html>
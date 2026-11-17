document.addEventListener('DOMContentLoaded', function () {
  const menuToggle = document.querySelector('.menu-toggle');
  const mainNav = document.querySelector('.main-nav');

  if (menuToggle && mainNav) {
    menuToggle.addEventListener('click', function () {
      mainNav.classList.toggle('abierto');
    });
  }

  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!window.confirm(form.dataset.confirm)) {
        event.preventDefault();
      }
    });
  });

  const imagenPrincipal = document.getElementById('imagen-principal');

  document.querySelectorAll('.miniatura').forEach(function (boton) {
    boton.addEventListener('click', function () {
      if (!imagenPrincipal) {
        return;
      }

      imagenPrincipal.src = boton.dataset.src;
      document.querySelectorAll('.miniatura').forEach(function (otro) {
        otro.classList.remove('activa');
      });
      boton.classList.add('activa');
    });
  });

  document.querySelectorAll('input[data-preview]').forEach(function (input) {
    input.addEventListener('change', function () {
      const destino = document.getElementById(input.dataset.preview);

      if (!destino || !input.files || !input.files[0]) {
        return;
      }

      const lector = new FileReader();
      lector.onload = function (evento) {
        destino.src = evento.target.result;
        destino.style.display = 'block';
      };
      lector.readAsDataURL(input.files[0]);
    });
  });

  document.querySelectorAll('[data-cantidad]').forEach(function (control) {
    const input = control.querySelector('input');

    if (!input) {
      return;
    }

    control.querySelectorAll('button[data-accion]').forEach(function (boton) {
      boton.addEventListener('click', function () {
        const actual = parseInt(input.value, 10) || 1;
        input.value = boton.dataset.accion === 'sumar' ? actual + 1 : Math.max(1, actual - 1);
      });
    });
  });

  document.querySelectorAll('.auto-submit').forEach(function (elemento) {
    elemento.addEventListener('change', function () {
      if (elemento.form) {
        elemento.form.submit();
      }
    });
  });

  const datosVariantes = document.getElementById('variantes-data');

  if (datosVariantes) {
    const variantes = JSON.parse(datosVariantes.textContent);
    const oculto = document.getElementById('variante_id');
    const textoStock = document.getElementById('stock-variante');
    const botonAgregar = document.getElementById('agregar-carrito');

    const actualizar = function () {
      const talla = document.querySelector('input[name="talla"]:checked')?.value;
      const color = document.querySelector('input[name="color"]:checked')?.value;
      const variante = variantes.find(function (item) {
        return item.talla === talla && item.color === color;
      });

      if (!variante) {
        oculto.value = '';
        textoStock.textContent = 'Selecciona una combinación disponible.';
        botonAgregar.disabled = true;
        return;
      }

      oculto.value = String(variante.id);
      textoStock.textContent = variante.stock > 0
        ? 'Disponible: ' + variante.stock + ' unidad(es)'
        : 'Sin stock disponible para esta combinación.';
      botonAgregar.disabled = variante.stock < 1;
    };

    document.querySelectorAll('input[name="talla"], input[name="color"]').forEach(function (radio) {
      radio.addEventListener('change', actualizar);
    });

    actualizar();
  }
});

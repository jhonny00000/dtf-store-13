<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a href="{{ route('home') }}" class="logo" style="color: white;">
                    <span class="logo-mark">13</span>
                    <span>DTF STORE 13<small>URBAN PRINT</small></span>
                </a>
                <p style="margin-top: 18px;">Poleras, hoodies y polos con estampado DTF de alta definición. Diseños urbanos, colores intensos y acabados profesionales para el equipo 13.</p>
            </div>
            <div>
                <h4>Navegación</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('catalogo.index') }}">Catálogo</a></li>
                    <li><a href="{{ route('carrito.index') }}">Carrito</a></li>
                    <li><a href="{{ route('pedidos.index') }}">Mis pedidos</a></li>
                </ul>
            </div>
            <div>
                <h4>Contacto</h4>
                <ul>
                    <li>Correo: ventas@dtfstore13.local</li>
                    <li>Teléfono: +591 70000013</li>
                    <li>Dirección: Zona Urbana, Propiedad 13</li>
                    <li>Horario: Lunes a sábado, 09:00 - 19:00</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">© {{ now()->year }} DTF Store 13. Proyecto académico de comercio electrónico.</div>
    </div>
</footer>

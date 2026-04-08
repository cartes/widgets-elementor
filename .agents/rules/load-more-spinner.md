---
description: Siempre que se agregue un botón de paginación (Cargar más), se debe agregar un spinner de carga.
---
Siempre que se implemente un botón de "Cargar más" o paginación por AJAX, asegúrate de:
1. Añadir feedback visual (un spinner o indicador de carga) mientras se procesa la solicitud AJAX.
2. Deshabilitar el botón durante la carga para evitar clics múltiples.
3. Asegurarse de que el script principal (ej. `elpl-widgets.js`) contemple la inyección de este spinner en el DOM justo encima o debajo del botón/grid de resultados.

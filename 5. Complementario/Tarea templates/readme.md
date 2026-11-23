# 📋 Tarea: Templates en Django

## 📖 Introducción
Dado el escenario del tema anterior de **Gestión de Tareas**, vamos a ampliar el proyecto integrando el sistema de **Templates**. 

> **📌 Entrega:** Deberéis entregar el enlace al repositorio de **Git** con todas las *templates* solicitadas debidamente implementadas.

---

## 🛠️ Ejercicios

1. **Barra de navegación:** Crea una *template* que contenga un menú de navegación superior con los enlaces principales de la página.
2. **Pie de página:** Crea una *template* dedicada exclusivamente al pie de página (*footer*) de la web.
3. **Plantilla base:** Crea una *template* principal que incluya las dos anteriores (`include`) y de la cual heredarán todas las demás páginas. Esta plantilla deberá definir **dos bloques (`{% block %}`)** principales:
   * Uno para la **cabecera** (`head` / título).
   * Otro para el **contenido** principal.
4. **Archivos estáticos:** Incluye un archivo **CSS**, un archivo **JavaScript** y una **imagen** dentro de la plantilla base anterior (usando `{% static %}`).
5. **Herencia de plantillas:** Haz que todas las páginas del proyecto hereden (`{% extends %}`) de la plantilla base creada en el paso 3.
6. **Refactorización de listas:** En todas las *templates* de tipo listado donde exista un bucle `{% for %}`, refactoriza el código extrayendo el contenido interior a una nueva *template* independiente e inclúyela dentro del bucle.
7. **Template Tags:** Utiliza al menos **5 *template tags* diferentes** en algunas páginas del proyecto (por ejemplo: `{% if %}...{% else %}`, `{% for %}...{% empty %}`, `{% url %}`, `{% with %}`, `{% csrf_token %}`, etc.).
8. **Operadores lógicos:** Emplea al menos **5 operadores lógicos o de comparación diferentes** dentro de tus etiquetas condicionales (por ejemplo: `==`, `!=`, `<`, `>`, `and`, `or`, `not`, `in`).
9. **Template Filters:** Aplica al menos **10 *template filters* diferentes** a lo largo del proyecto (por ejemplo: `|date`, `|length`, `|upper`, `|lower`, `|truncatewords`, `|default`, `|title`, `|capfirst`, `|linebreaks`, `|pluralize`, etc.).

---

## ✅ Checklist de Revisión
- [ ] Menú de navegación creado e incluido.
- [ ] Footer creado e incluido.
- [ ] Plantilla base con bloques de cabecera y contenido.
- [ ] Estáticos cargados (CSS, JS e imagen).
- [ ] Herencia aplicada en todas las vistas.
- [ ] Ítems de bucles `for` refactorizados en sub-plantillas.
- [ ] Mínimo 5 *template tags* distintos.
- [ ] Mínimo 5 operadores lógicos distintos.
- [ ] Mínimo 10 *template filters* distintos.
- [ ] Enlace de Git listo para entregar.
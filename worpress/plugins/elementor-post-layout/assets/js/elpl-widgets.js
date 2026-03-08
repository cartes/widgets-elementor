/**
 * ELPL Widgets - Global JavaScript
 *
 * Provides shared functionality for all Elementor Post Layout widgets.
 * Currently handles: load-more pagination (mobile only, controlled via CSS).
 */
(function () {
  "use strict";

  function initLoadMore() {
    document.addEventListener("click", function (e) {
      var btn = e.target.closest(".elpl-load-more-btn");
      if (!btn) return;
      if (
        btn.classList.contains("elpl-loading") ||
        btn.classList.contains("elpl-no-more")
      )
        return;

      if (typeof window.elplWidgets === "undefined") {
        console.error(
          "[ELPL] elplWidgets not defined. Script may not be localised.",
        );
        return;
      }

      var wrap = btn.closest(".elpl-load-more-wrap");
      var moduleEl = btn.closest("[data-elpl-module]");
      var gridSelector = btn.getAttribute("data-grid") || ".elpl-posts-grid";
      var grid = moduleEl ? moduleEl.querySelector(gridSelector) : null;
      if (!grid) return;

      var widget = btn.getAttribute("data-widget") || "";
      var category = btn.getAttribute("data-category") || "";
      var perPage = parseInt(btn.getAttribute("data-per-page"), 10) || 7;
      var offset = parseInt(btn.getAttribute("data-offset"), 10) || 0;
      var dateFormat = btn.getAttribute("data-date-format") || "d M Y";
      var showDate = btn.getAttribute("data-show-date") || "yes";
      var showExcerpt = btn.getAttribute("data-show-excerpt") || "yes";
      var postType = btn.getAttribute("data-post-type") || "post";
      var metaPersona = btn.getAttribute("data-meta-persona") || "";
      var metaCargo = btn.getAttribute("data-meta-cargo") || "";
      var showImage = btn.getAttribute("data-show-image") || "yes";
      var categoryId = btn.getAttribute("data-category-id") || "";
      var metaType = btn.getAttribute("data-meta-type") || "date";
      var metaData = btn.getAttribute("data-meta-data") || "";
      var metaSeparator = btn.getAttribute("data-meta-separator") || "///";
      var taxonomy = btn.getAttribute("data-taxonomy") || "";
      var termId = btn.getAttribute("data-term-id") || "";
      var authorId = btn.getAttribute("data-author-id") || "";

      // Si hay posts ocultos en mobile (ya en el DOM), revelarlos antes de ir al servidor
      var hiddenCount = parseInt(
        btn.getAttribute("data-hidden-count") || "0",
        10,
      );
      if (hiddenCount > 0) {
        var hiddenPosts = grid.querySelectorAll(".elpl-top-post--m-hidden");
        hiddenPosts.forEach(function (el) {
          el.classList.remove("elpl-top-post--m-hidden");
        });
        btn.setAttribute("data-hidden-count", "0");
        // Si no hay más en DB, ocultar el botón
        if (btn.getAttribute("data-has-db-more") !== "true") {
          if (wrap) wrap.style.display = "none";
        }
        return;
      }

      // Show spinner before the wrap, hide button
      var spinner = document.createElement("div");
      spinner.className = "elpl-loading-spinner";
      spinner.style.display = "block";
      if (wrap && wrap.parentNode) {
        wrap.parentNode.insertBefore(spinner, wrap);
      } else if (grid && grid.parentNode) {
        grid.parentNode.appendChild(spinner);
      }

      btn.classList.add("elpl-loading");
      btn.disabled = true;
      if (wrap) wrap.style.visibility = "hidden";

      var params = new URLSearchParams();
      params.append("action", "elpl_load_more_posts");
      params.append("nonce", window.elplWidgets.nonce);
      params.append("widget", widget);
      params.append("category", category);
      params.append("per_page", perPage);
      params.append("offset", offset);
      params.append("date_format", dateFormat);
      params.append("show_date", showDate);
      params.append("show_excerpt", showExcerpt);
      params.append("post_type", postType);
      params.append("meta_persona", metaPersona);
      params.append("meta_cargo", metaCargo);
      params.append("show_image", showImage);
      params.append("category_id", categoryId);
      params.append("meta_type", metaType);
      params.append("meta_data", metaData);
      params.append("meta_separator", metaSeparator);
      params.append("taxonomy", taxonomy);
      params.append("term_id", termId);
      params.append("author_id", authorId);

      fetch(window.elplWidgets.ajaxUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: params.toString(),
        credentials: "same-origin",
      })
        .then(function (response) {
          if (!response.ok) throw new Error("HTTP " + response.status);
          return response.json();
        })
        .then(function (json) {
          if (!json || !json.data) {
            console.error("[ELPL] Unexpected response", json);
            return;
          }
          if (json.success && json.data.html) {
            grid.insertAdjacentHTML("beforeend", json.data.html);
          }
          btn.setAttribute("data-offset", json.data.next_offset);

          if (!json.data.has_more) {
            if (wrap) wrap.style.display = "none";
          }
        })
        .catch(function (err) {
          console.error("[ELPL] Load more error:", err);
        })
        .finally(function () {
          // Remove spinner, restore button
          spinner.parentNode && spinner.parentNode.removeChild(spinner);
          btn.classList.remove("elpl-loading");
          btn.disabled = false;
          if (wrap && wrap.style.display !== "none") {
            wrap.style.visibility = "";
          }
        });
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initLoadMore);
  } else {
    initLoadMore();
  }
})();

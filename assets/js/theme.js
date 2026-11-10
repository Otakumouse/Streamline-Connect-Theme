(function () {
	/*
	 * Same reveal as Alpha Solar Tech. Hero blocks use data-reveal-on="load".
	 * Other blocks fade up 20px when 15% of them enters the viewport.
	 * ?noreveal or reduced motion shows everything at once.
	 */
	var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
	var skipReveal = reduceMotion || new URLSearchParams(window.location.search).has("noreveal");

	function revealAll(nodes) {
		nodes.forEach(function (node) {
			node.classList.add("is-revealed");
		});
	}

	var revealNodes = Array.prototype.slice.call(document.querySelectorAll("[data-reveal]"));

	if (skipReveal || !("IntersectionObserver" in window)) {
		revealAll(revealNodes);
	} else {
		var loadNodes = revealNodes.filter(function (node) {
			return node.getAttribute("data-reveal-on") === "load";
		});
		window.requestAnimationFrame(function () {
			window.requestAnimationFrame(function () {
				revealAll(loadNodes);
			});
		});

		var revealObserver = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}
					entry.target.classList.add("is-revealed");
					revealObserver.unobserve(entry.target);
				});
			},
			{ threshold: 0.15, rootMargin: "0px 0px -10% 0px" }
		);

		revealNodes.forEach(function (node) {
			if (node.getAttribute("data-reveal-on") !== "load") {
				revealObserver.observe(node);
			}
		});
	}

	var header = document.querySelector("[data-header]");
	if (!header) {
		return;
	}

	var menuBtn = header.querySelector("[data-menu-toggle]");
	var capBtn = header.querySelector("[data-capabilities-toggle]");
	var mega = document.getElementById("capabilities_menu");
	var scrim = document.querySelector("[data-scrim]");
	var desktop = window.matchMedia("(min-width: 1440px)");
	var reduce = window.matchMedia("(prefers-reduced-motion: reduce)");

	function menuOpen() {
		return header.classList.contains("is-menu-open");
	}

	function capsOpen() {
		return header.classList.contains("is-capabilities-open");
	}

	function setOpen(menu, caps) {
		header.classList.toggle("is-menu-open", menu);
		header.classList.toggle("is-capabilities-open", caps);
		if (menuBtn) {
			menuBtn.setAttribute("aria-expanded", menu ? "true" : "false");
		}
		if (capBtn) {
			capBtn.setAttribute("aria-expanded", caps ? "true" : "false");
		}
		if (mega) {
			mega.hidden = !caps;
		}
		if (scrim) {
			scrim.hidden = !(menu || caps);
		}
		document.body.classList.toggle("nav-lock", menu || caps);
	}

	function closeMenus(focusEl) {
		setOpen(false, false);
		if (focusEl && typeof focusEl.focus === "function") {
			focusEl.focus();
		}
	}

	function focusable(root) {
		return Array.prototype.filter.call(
			root.querySelectorAll("a, button, [tabindex]:not([tabindex='-1'])"),
			function (el) {
				return !el.hidden && el.getClientRects().length > 0;
			}
		);
	}

	if (menuBtn) {
		menuBtn.addEventListener("click", function () {
			if (menuOpen()) {
				closeMenus(menuBtn);
				return;
			}
			setOpen(true, false);
			var first = header.querySelector(".nav-cta--menu");
			if (first) {
				first.focus();
			}
		});
	}

	if (capBtn) {
		capBtn.addEventListener("click", function () {
			if (capsOpen()) {
				if (desktop.matches) {
					closeMenus(capBtn);
				} else {
					setOpen(true, false);
					capBtn.focus();
				}
				return;
			}
			if (desktop.matches) {
				setOpen(false, true);
			} else {
				setOpen(true, true);
			}
		});
	}

	if (scrim) {
		scrim.addEventListener("click", function () {
			closeMenus(menuOpen() ? menuBtn : capBtn);
		});
	}

	document.addEventListener("keydown", function (event) {
		if (event.key === "Escape" && (menuOpen() || capsOpen())) {
			event.preventDefault();
			closeMenus(menuOpen() ? menuBtn : capBtn);
			return;
		}

		if (event.key !== "Tab" || (!menuOpen() && !capsOpen())) {
			return;
		}

		var root = menuOpen() ? header.querySelector("#site_nav") : header;
		var items = focusable(root);
		if (!items.length) {
			return;
		}

		var first = items[0];
		var last = items[items.length - 1];
		if (event.shiftKey && document.activeElement === first) {
			event.preventDefault();
			last.focus();
		} else if (!event.shiftKey && document.activeElement === last) {
			event.preventDefault();
			first.focus();
		}
	});

	desktop.addEventListener("change", function () {
		closeMenus(null);
	});

	document.querySelectorAll('a[href^="#"]').forEach(function (link) {
		link.addEventListener("click", function (event) {
			var id = link.getAttribute("href").slice(1);
			var behavior = reduce.matches ? "auto" : "smooth";

			if (!id || id === "top") {
				event.preventDefault();
				window.scrollTo({ top: 0, behavior: behavior });
				var logo = document.querySelector(".logo");
				if (logo) {
					logo.focus({ preventScroll: true });
				}
				closeMenus(null);
				return;
			}

			var target = document.getElementById(id);
			if (!target) {
				return;
			}

			event.preventDefault();
			target.scrollIntoView({ behavior: behavior, block: "start" });
			if (!target.hasAttribute("tabindex")) {
				target.setAttribute("tabindex", "-1");
			}
			target.focus({ preventScroll: true });
			closeMenus(null);
		});
	});
})();

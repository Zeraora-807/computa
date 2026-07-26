(() => {
    const body = document.body;
    document.querySelector('[data-menu-toggle]')?.addEventListener('click', () => body.classList.toggle('menu-open'));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') body.classList.remove('menu-open');
        if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
            const search = document.querySelector('#archive-search');
            if (search) {
                event.preventDefault();
                search.focus();
                search.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    document.querySelectorAll('[data-category-toggle]').forEach(button => {
        const node = button.closest('[data-category-node]');
        if (!node) return;
        button.addEventListener('click', () => {
            const collapsed = node.classList.toggle('is-collapsed');
            button.setAttribute('aria-expanded', String(!collapsed));
        });
    });

    const content = document.querySelector('[data-entry-content]');
    const toc = document.querySelector('[data-toc]');
    if (content && toc) {
        const headings = [...content.querySelectorAll('h2,h3,h4')];
        const lastItemAtLevel = new Map();
        const linkById = new Map();

        headings.forEach((heading, index) => {
            heading.id ||= `section-${index + 1}`;
            const level = Number(heading.tagName.slice(1));
            const item = document.createElement('li');
            item.className = `toc-level-${level}`;
            const link = document.createElement('a');
            link.href = `#${heading.id}`;
            link.textContent = heading.textContent;
            item.append(link);

            let parentItem = null;
            for (let parentLevel = level - 1; parentLevel >= 2; parentLevel -= 1) {
                if (lastItemAtLevel.has(parentLevel)) {
                    parentItem = lastItemAtLevel.get(parentLevel);
                    break;
                }
            }

            if (parentItem) {
                let nested = [...parentItem.children].find(child => child.tagName === 'OL');
                if (!nested) {
                    nested = document.createElement('ol');
                    parentItem.append(nested);
                }
                nested.append(item);
            } else {
                toc.append(item);
            }

            lastItemAtLevel.set(level, item);
            [...lastItemAtLevel.keys()].filter(key => key > level).forEach(key => lastItemAtLevel.delete(key));
            linkById.set(heading.id, link);
        });

        if (!headings.length) {
            toc.closest('.aside-block')?.remove();
        } else if ('IntersectionObserver' in window) {
            const headingObserver = new IntersectionObserver(entries => {
                const visible = entries.filter(entry => entry.isIntersecting).sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top);
                if (!visible.length) return;
                linkById.forEach(link => link.classList.remove('is-active'));
                linkById.get(visible[0].target.id)?.classList.add('is-active');
            }, { rootMargin: '-18% 0px -68% 0px', threshold: 0 });
            headings.forEach(heading => headingObserver.observe(heading));
        }
    }

    document.querySelectorAll('.entry-content img').forEach((image, index) => {
        image.decoding = 'async';
        if (index > 0) image.loading = 'lazy';
    });
    document.querySelectorAll('.entry-content table').forEach(table => {
        const wrapper = document.createElement('div');
        wrapper.className = 'table-scroll';
        table.parentNode.insertBefore(wrapper, table);
        wrapper.append(table);
    });

    const escapeHtml = value => value.replace(/[&<>]/g, character => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;' })[character]);
    const highlight = value => {
        const pattern = /\/\*[\s\S]*?\*\/|\/\/[^\n]*|<!--[\s\S]*?-->|"(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'|\b(?:as|async|await|break|case|catch|class|const|continue|default|do|echo|else|elseif|export|extends|false|finally|for|foreach|from|function|if|import|in|instanceof|interface|let|match|new|null|private|protected|public|return|static|switch|throw|trait|true|try|use|var|while|yield)\b|\b\d+(?:\.\d+)?\b/g;
        let output = '', cursor = 0;
        for (const match of value.matchAll(pattern)) {
            output += escapeHtml(value.slice(cursor, match.index));
            const token = match[0];
            const type = token.startsWith('//') || token.startsWith('/*') || token.startsWith('<!--') ? 'comment'
                : token.startsWith('"') || token.startsWith("'") ? 'string'
                    : /^\d/.test(token) ? 'number' : 'keyword';
            output += `<span class="hl-${type}">${escapeHtml(token)}</span>`;
            cursor = match.index + token.length;
        }
        return output + escapeHtml(value.slice(cursor));
    };
    document.querySelectorAll('.entry-content pre').forEach(pre => {
        const code = pre.querySelector('code') || pre;
        const source = code.textContent;
        code.innerHTML = highlight(source);
        const copy = document.createElement('button');
        copy.type = 'button';
        copy.className = 'copy-code';
        copy.textContent = 'copy';
        copy.addEventListener('click', async () => {
            await navigator.clipboard.writeText(source);
            copy.textContent = 'copied';
            setTimeout(() => copy.textContent = 'copy', 1400);
        });
        pre.append(copy);
    });

    if ('IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
        const observer = new IntersectionObserver(entries => entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        }), { rootMargin: '0px 0px -5% 0px' });
        document.querySelectorAll('.reveal').forEach(element => observer.observe(element));
    } else {
        document.querySelectorAll('.reveal').forEach(element => element.classList.add('is-visible'));
    }
})();

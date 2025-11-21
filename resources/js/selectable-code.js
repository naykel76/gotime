/**
 * Selectable Code Blocks
 * Allows users to select multiple code blocks and copy them all at once
 */
document.addEventListener('DOMContentLoaded', function() {
    // Check if there are any selectable code blocks on the page
    const selectableBlocks = document.querySelectorAll('.selectable-code-block[data-selectable="true"]');
    
    if (selectableBlocks.length === 0) {
        return;
    }

    // Add hover and selection styles
    const style = document.createElement('style');
    style.textContent = `
        .selectable-code-block[data-selectable="true"] {
            position: relative;
        }
        .selectable-code-block[data-selectable="true"]:hover:not(.selected) {
            outline: 2px solid rgba(102, 126, 234, 0.3);
            outline-offset: -2px;
        }
        .selectable-code-block[data-selectable="true"]:hover:not(.selected)::before {
            content: 'Click to select';
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: rgba(102, 126, 234, 0.9);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 10;
            pointer-events: none;
        }
        .selectable-code-block.selected {
            outline: 3px solid #667eea;
            outline-offset: -3px;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .selectable-code-block.selected::before {
            content: '✓ Selected';
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: #667eea;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 10;
            pointer-events: none;
        }
    `;
    document.head.appendChild(style);

    // Create and inject the floating action button
    const fab = document.createElement('div');
    fab.id = 'copy-selected-fab';
    fab.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        display: none;
        gap: 0.5rem;
        align-items: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06);
        cursor: pointer;
        font-weight: 600;
        z-index: 9999;
        transition: all 0.3s ease;
    `;
    
    fab.innerHTML = `
        <button id="clear-selected-btn" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.875rem; cursor: pointer; margin-right: 0.25rem;">✕</button>
        <span id="selected-count" style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.5rem; border-radius: 1rem; font-size: 0.875rem;">0</span>
        <span>Copy Selected</span>
    `;
    
    document.body.appendChild(fab);

    // Track selected blocks
    const selectedBlocks = new Set();

    function updateFAB() {
        const countSpan = document.getElementById('selected-count');
        
        if (selectedBlocks.size > 0) {
            fab.style.display = 'flex';
            countSpan.textContent = selectedBlocks.size;
        } else {
            fab.style.display = 'none';
        }
    }

    // Add click listeners to all selectable blocks
    selectableBlocks.forEach(block => {
        block.addEventListener('click', function(e) {
            // Don't toggle if clicking the copy button
            if (e.target.closest('button')) {
                return;
            }

            const preElement = this.querySelector('pre');
            const codeId = preElement ? preElement.id : null;
            
            if (!codeId) return;

            // Toggle selection
            if (selectedBlocks.has(codeId)) {
                selectedBlocks.delete(codeId);
                this.classList.remove('selected');
            } else {
                selectedBlocks.add(codeId);
                this.classList.add('selected');
            }
            
            updateFAB();
        });
    });

    // Handle clear/deselect action
    document.getElementById('clear-selected-btn').addEventListener('click', function(e) {
        e.stopPropagation();
        
        selectableBlocks.forEach(block => {
            block.classList.remove('selected');
        });
        selectedBlocks.clear();
        updateFAB();
    });

    // Handle copy action
    fab.addEventListener('click', async function() {
        if (selectedBlocks.size === 0) return;

        const codes = [];
        selectedBlocks.forEach(codeId => {
            const codeElement = document.getElementById(codeId);
            if (codeElement) {
                const code = codeElement.getAttribute('data-code');
                codes.push(code);
            }
        });

        const textToCopy = codes.join('\n');

        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(textToCopy);
            } else {
                const textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                textarea.style.position = 'fixed';
                textarea.style.left = '-999999px';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }

            // Visual feedback
            const originalText = fab.innerHTML;
            fab.innerHTML = '<span>✓ Copied!</span>';
            fab.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            
            setTimeout(() => {
                // Clear all selections after copy
                selectableBlocks.forEach(block => {
                    block.classList.remove('selected');
                });
                selectedBlocks.clear();
                
                fab.innerHTML = originalText;
                fab.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                updateFAB();
            }, 2000);

        } catch (err) {
            console.error('Failed to copy:', err);
        }
    });

    // Hover effect
    fab.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
        this.style.boxShadow = '0 10px 15px rgba(0, 0, 0, 0.2), 0 4px 6px rgba(0, 0, 0, 0.1)';
    });

    fab.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
        this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06)';
    });
});

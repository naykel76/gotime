# Container Testing

This file tests all three container types with the new LayoutRegistry system.

## Test 1: Collapse Card (Default)

::: collapse title="Click to expand"
This is a **card-style** collapsible section.

- It should have a white background
- Border and shadow
- Chevron icon that rotates
- Full-width button with hover effect
:::

## Test 2: Collapse Card (Opened by default)

::: collapse title="This one starts open" opened
This collapse starts in the **open** state.

You should see this content immediately without clicking.
:::

## Test 3: Collapse Button (Simple style)

::: collapse-button title="Show More"
This is a **simple button-style** collapse.

- No card styling
- Simple button
- No fancy borders or shadows
- Just clean and minimal
:::

## Test 4: Box Container

::: box title="Note" class="danger"
This is a **box container**.

It's always visible (not collapsible) and perfect for notes, warnings, tips, etc.
:::

## Test 5: Box Container (No title)

::: box class="info"
This box has no title, just content with a custom class.
:::

## Test 6: Nested Content

::: collapse title="Complex Content"
This collapse contains:

### A heading

Some **bold text** and _italic text_.

- List item 1
- List item 2

```javascript +preview
console.log("Code block inside collapse!");
```

Another paragraph with more content.
:::

## Test 7: Code Block with Collapse (Backward Compatibility)

This tests that the old code block system still works with the new LayoutRegistry:

```html +preview +code
<div class="example">
    <h1>Hello World</h1>
    <p>This should work with the legacy bridge.</p>
</div>
```

# Markdown Extensions

Custom CommonMark extensions for enhanced markdown rendering in Laravel.

## Extensions Included

### 1. Code Block Extension

Enhanced code blocks with Torchlight syntax highlighting and preview functionality.

**Features:**

- Syntax highlighting via Torchlight
- Copy-to-clipboard button
- Optional code preview toggle
- Long code line handling (stores code in hidden textarea)

**Usage:**

```markdown
```php
// Your code here
```

```

### 2. Component Extension
Flexible container-based components using `:::` delimiter syntax.

**Features:**
- Multiple built-in styles (collapse, button, box, show-more)
- Attribute support (title, class, opened flag)
- Easy to extend with custom styles
- Clean separation of parsing and rendering logic

**Usage:**
```markdown
::: collapse title="Click to expand"
Your content here
:::

::: box class="bg-blue-100"
Info box content
:::

::: show-more
Long content that can be toggled
:::
```

## Component Extension Architecture

### Directory Structure

```
Component/
├── ComponentExtension.php          # Entry point - register styles here
├── Parsing/                        # Internal parser logic
│   ├── ComponentBlock.php          # AST node representation
│   ├── ComponentStartParser.php   # Recognizes ::: syntax
│   └── ComponentParser.php         # Continues parsing until closing :::
├── Rendering/                      # Internal renderer logic
│   ├── ComponentRenderer.php       # Delegates to registered styles
│   └── StyleRegistry.php           # Maps type names to style classes
└── Styles/                         # User-extensible component styles
    ├── StyleInterface.php
    ├── Card.php                    # Card-style collapsible
    ├── Button.php                  # Simple button collapsible
    ├── Box.php                     # Always-visible info boxes
    └── ShowMore.php                # Toggle with "Show More/Less" button
```

### Built-in Component Styles

#### `collapse` (Card style)

Card-style collapsible with border, shadow, and rotating chevron icon.

```markdown
::: collapse title="FAQ Question" opened
Answer content
:::
```

#### `collapse-button` (Button style)

Minimal collapsible with simple button and no card styling.

```markdown
::: collapse-button title="Show Details"
Hidden details
:::
```

#### `box`

Always-visible styled box for notes, warnings, tips.

```markdown
::: box title="Note" class="bg-yellow-100 bdr-yellow-300"
Important information
:::
```

#### `show-more`

Minimal toggle for collapsing long content under headings. Button text auto-switches between "Show More" and "Show Less".

```markdown
## Long Section Heading

::: show-more
Lots of content that will be initially hidden
:::
```

### Adding Custom Styles

**1. Create a Style Class**

Implement `StyleInterface` in `Component/Styles/`:

```php
namespace Naykel\Gotime\Extensions\Markdown\Component\Styles;

class MyCustomStyle implements StyleInterface
{
    public function render(string $content, array $attributes): string
    {
        $title = $attributes['title'] ?? 'Default Title';
        $class = $attributes['class'] ?? '';
        
        return "<div class=\"my-custom {$class}\">
            <h3>{$title}</h3>
            {$content}
        </div>";
    }
}
```

**2. Register the Style**

In `ComponentExtension.php`:

```php
StyleRegistry::register('my-custom', MyCustomStyle::class);
```

**3. Use It**

```markdown
::: my-custom title="Hello"
Content goes here
:::
```

## Attribute Syntax

Components support three types of attributes:

### Quoted Attributes

```markdown
::: collapse title="My Title" class="foo bar"
```

### Unquoted Attributes

```markdown
::: box title=Info class=warning
```

### Boolean Flags

```markdown
::: collapse opened
Content is visible by default
:::
```

## Support Utilities

### AttributeParser

Static utility class for parsing attributes from info strings.

```php
use Naykel\Gotime\Extensions\Markdown\Support\AttributeParser;

// Parse all attributes
$attrs = AttributeParser::parse('title="Hello" class="foo" opened');
// Returns: ['title' => 'Hello', 'class' => 'foo', 'opened' => true]

// Extract specific attribute
$title = AttributeParser::extractAttribute('title="Hello"', 'title');
// Returns: 'Hello'
```

## Configuration

Register extensions in `config/markdown.php`:

```php
'extensions' => [
    // ... other extensions
    \Naykel\Gotime\Extensions\Markdown\CodeRendererExtension::class,
    \Naykel\Gotime\Extensions\Markdown\Component\ComponentExtension::class,
],
```

## Design Decisions

### Why "Component" instead of "Container"?

- More familiar terminology for users ("add a collapse component")
- Aligns with modern web development language
- "Container" felt too generic

### Why Static StyleRegistry?

- CommonMark requires extensions to be instantiable without constructor params
- Static registry allows styles to be registered once at extension initialization
- Simpler than dependency injection in this context

### Why Separate Parsing/ and Rendering/?

- Clear separation of concerns
- Parsing logic (how to recognize `::: syntax`) is stable
- Rendering logic (how to output HTML) varies per style
- Users only need to interact with Styles/ directory for customization

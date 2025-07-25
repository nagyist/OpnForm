<?php

namespace App\Service\AI\Prompts\Form;

use App\Service\AI\Prompts\Prompt;

class GenerateFormPrompt extends Prompt
{
    protected ?float $temperature = null;

    protected ?int $maxTokens = null;

    protected string $model = 'o4-mini';

    /**
     * The prompt template for generating forms
     */
    public const PROMPT_TEMPLATE = <<<'EOD'
        Help me build the json structure for the form described below, be as accurate as possible.

        <form_description>
            {formPrompt}
        </form_description>
        
        Forms are represented as Json objects. There are several input types and layout block types (type start with nf-).
        You can use for instance nf-text to add a title or text to the form using some basic html (h1, p, b, i, u etc).
        Order of blocks matters.

        Available field types:
        - text: Text input (use multi_lines: true for multi-line text)
        - rich_text: Rich text input
        - date: Date picker (use with_time: true to include time selection)
        - url: URL input with validation
        - phone_number: Phone number input
        - email: Email input with validation
        - checkbox: Single checkbox for yes/no (use use_toggle_switch: true for toggle switch)
        - select: Dropdown selection (use without_dropdown: true for radio buttons, recommended for <5 options)
        - multi_select: Multiple selection (use without_dropdown: true for checkboxes, recommended for <5 options)
        - matrix: Matrix input with rows and columns
        - number: Numeric input
        - rating: Star rating 
        - scale: Numeric scale 
        - slider: Slider selection
        - files: File upload
        - signature: Signature pad
        - barcode: Barcode scanner
        - nf-text: Rich text content (not an input field)
        - nf-page-break: Page break for multi-page forms
        - nf-divider: Visual divider (not an input field)
        - nf-image: Image element
        - nf-code: Code block
        
        HTML formatting for nf-text:
        - Headers: <h1>, <h2> for section titles and subtitles
        - Text formatting: <b> or <strong> for bold, <i> or <em> for italic, <u> for underline, <s> for strikethrough
        - Links: <a href="url">link text</a> for hyperlinks
        - Lists: <ul><li>item</li></ul> for bullet lists, <ol><li>item</li></ol> for numbered lists
        - Colors: <span style="color: #hexcode">colored text</span> for colored text
        - Paragraphs: <p>paragraph text</p> for text blocks with spacing
        Use these HTML tags to create well-structured and visually appealing form content.
        
        Field width options:
        - width: "full" (default, takes entire width)
        - width: "1/2" (takes half width)
        - width: "1/3" (takes a third of the width)
        - width: "2/3" (takes two thirds of the width)
        - width: "1/4" (takes a quarter of the width)
        - width: "3/4" (takes three quarters of the width)
        Fields with width less than "full" will be placed on the same line if there's enough room. For example:
        - Two 1/2 width fields will be placed side by side
        - Three 1/3 width fields will be placed on the same line
        - etc.
        No need for lines width to be complete. Don't abuse putting multiple fields on the same line if it doens't make sense. For First name and Last name, it works well for instance.
        
        If the form is too long, you can paginate it by adding one or multiple page breaks (nf-page-break).
        
        Create a complete form with appropriate fields based on the description. Include:
        - A clear `title` (internal for form admin)
        - `nf-text` blocks to add a title or text to the form using some basic html (h1, p, b, i, u etc)
        - Logical field grouping
        - Required fields where necessary (do not add * to the field name if required - it's done automatically)
        - Help text for complex fields
        - Appropriate validation
        - Customized submission text
    EOD;

    /**
     * JSON schema for form output
     */
    protected ?array $jsonSchema = [
        'type' => 'object',
        'required' => ['title', 'properties', 're_fillable', 'use_captcha', 'redirect_url', 'submitted_text', 'uppercase_labels', 'submit_button_text', 're_fill_button_text', 'color'],
        'additionalProperties' => false,
        'properties' => [
            'title' => [
                'type' => 'string',
                'description' => 'The title of the form (default: "New Form")'
            ],
            're_fillable' => [
                'type' => 'boolean',
                'description' => 'Whether the form can be refilled after submission (default: false)'
            ],
            'use_captcha' => [
                'type' => 'boolean',
                'description' => 'Whether to use CAPTCHA for spam protection (default: false)'
            ],
            'redirect_url' => [
                'type' => ['string', 'null'],
                'description' => 'URL to redirect to after submission (default: null)'
            ],
            'submitted_text' => [
                'type' => 'string',
                'description' => 'Text to display after form submission (default: "<p>Thank you for your submission!</p>")'
            ],
            'uppercase_labels' => [
                'type' => 'boolean',
                'description' => 'Whether to display field labels in uppercase (default: false)'
            ],
            'submit_button_text' => [
                'type' => 'string',
                'description' => 'Text for the submit button (default: "Submit")'
            ],
            're_fill_button_text' => [
                'type' => 'string',
                'description' => 'Text for the refill button (default: "Fill Again")'
            ],
            'color' => [
                'type' => 'string',
                'description' => 'Primary color for the form (default: "#64748b")'
            ],
            'properties' => [
                'type' => 'array',
                'description' => 'Array of form fields and elements',
                'items' => [
                    'anyOf' => [
                        ['$ref' => '#/definitions/textProperty'],
                        ['$ref' => '#/definitions/richTextProperty'],
                        ['$ref' => '#/definitions/dateProperty'],
                        ['$ref' => '#/definitions/urlProperty'],
                        ['$ref' => '#/definitions/phoneNumberProperty'],
                        ['$ref' => '#/definitions/emailProperty'],
                        ['$ref' => '#/definitions/checkboxProperty'],
                        ['$ref' => '#/definitions/selectProperty'],
                        ['$ref' => '#/definitions/multiSelectProperty'],
                        ['$ref' => '#/definitions/matrixProperty'],
                        ['$ref' => '#/definitions/numberProperty'],
                        ['$ref' => '#/definitions/ratingProperty'],
                        ['$ref' => '#/definitions/scaleProperty'],
                        ['$ref' => '#/definitions/sliderProperty'],
                        ['$ref' => '#/definitions/filesProperty'],
                        ['$ref' => '#/definitions/signatureProperty'],
                        ['$ref' => '#/definitions/barcodeProperty'],
                        ['$ref' => '#/definitions/nfTextProperty'],
                        ['$ref' => '#/definitions/nfPageBreakProperty'],
                        ['$ref' => '#/definitions/nfDividerProperty'],
                        ['$ref' => '#/definitions/nfImageProperty'],
                        ['$ref' => '#/definitions/nfCodeProperty']
                    ]
                ]
            ]
        ],
        'definitions' => FormFieldSchemas::FIELD_TYPE_DEFINITIONS
    ];

    public function __construct(
        public string $formPrompt
    ) {
        parent::__construct();
    }

    protected function getSystemMessage(): ?string
    {
        return 'You are an AI assistant specialized in creating form structures. Design intuitive, user-friendly forms that capture all necessary information based on the provided description.';
    }

    protected function getPromptTemplate(): string
    {
        return self::PROMPT_TEMPLATE;
    }

    /**
     * Override the execute method to automatically process the output
     */
    public function execute(): array
    {
        $formData = parent::execute();
        return $this->processOutput($formData);
    }

    /**
     * Process the AI output to ensure it meets our requirements
     */
    public function processOutput(array $formData): array
    {
        if (isset($formData['properties']) && is_array($formData['properties'])) {
            $formData['properties'] = FormFieldSchemas::processFields($formData['properties']);
        }

        // Clean title data
        if (isset($formData['title'])) {
            // Remove quotes if the title is enclosed in them
            $formData['title'] = preg_replace('/^["\'](.*)["\']$/', '$1', $formData['title']);
        }

        return $formData;
    }
}

<?php

namespace App\Names;


class Parser
{
    /**
     * @property string[] $name  Array of HomeOwners from the parsed names
     */
    private array $home_owners;

    /**
     * @property string[] $delimeters   Array of strings that acts as name delimeters
     */
    private array $delimeters = ['&', 'and'];

    /**
     * @var string[] $titles    Array of strings that are treated as titles
     */
    private array $titles = [
        'Dr',
        'Mister',
        'Mr',
        'Mrs',
        'Ms',
        'Prof'
    ];

    /**
     * Uses PHP 8 constructor property promotion
     */
    public function __construct(private string $input = '')
    {
    }

    /**
     * Start the parsing process
     * @return array
     */
    public function parse(): array
    {
        /**
         * @var string[] $split_input   A name string split based on name delimeters from the $this->delimeters property
         */
        $split_input = preg_split('/ (' . implode('|', $this->delimeters) . ') /', $this->input);

        if (count($split_input) > 1) {
            // Multiple names are present
            $this->home_owners = $this->parse_multiple_names($split_input);
        } else {
            // Only a single name is present
            $this->home_owners[] = $this->parse_single_name($split_input[0]);
        }

        return $this->home_owners;
    }

    /** =================================================================
     *  PRIVATE
     *  ================================================================= */

    /**
     * Parsing name elements when multiple names are present
     *
     * @param string[] $name_elements
     * @return string[]
     */
    private function parse_multiple_names(array $name_elements): array
    {
        /**
         * Two scenarios need to be catered for:
         *  1. '[title] [delimeter] [title] [name]' (linked names)
         *  2. '[title] [name] [delimeter] [title] [name]' (distinct names)
         */

        /**
         * @var [][] $home_owners    Return value
         */
        $home_owners = [];

        /** Establish if the array contains paired titles or seperately titled names */
        if ($this->paired_titles_exists($name_elements)) {
            /** Scenario #1 (linked names, e.g. 'Mr and Mrs Smith') */
            $home_owners = $this->parse_linked_names($name_elements);
        } else {
            /** Scenario #2 (multiple distinct names, e.g. 'Mr Smith and Ms Taylor') */

            /** Each element of the array is treated as a distinct name and can be parsed as such */
            foreach ($name_elements as $name_element) {
                /** Adds homeowner array to return value */
                $home_owners[] = $this->parse_single_name($name_element);
            }
        }

        /** Return the array of homeowners */
        return $home_owners;
    }

    /**
     * Description for function
     * @param string[] $name_elements
     * @return string[]
     *
     */
    function parse_linked_names(array $name_elements)
    {
        /**
         * @var string[] $home_owners Return value
         */
        $home_owners = [];

        /**
         * @var int $name_elements_extent   The extent of the array index, counting from 0
         */
        $name_elements_extent = count($name_elements) - 1;

        /** Reverse loop over the $name_elements array */
        for ($i = $name_elements_extent; $i >= 0; $i--) {
            if ($i === $name_elements_extent) {
                $home_owners[] = $this->parse_single_name($name_elements[$i]); // Parse last index of the array as a full name and title
            } else {
                /**
                 * The other elements of the array are used if they are valid titles, as defined in
                 * the $this->titles property.
                 * */
                if (array_search($name_elements[$i], $this->titles) !== false) { // Test explicitly for false as 0 should be true
                    $home_owner_copy = $home_owners[0]; // Clone the first homeowner array created in this loop
                    $home_owner_copy['title'] = $name_elements[$i]; // Set the title to match the current array element value
                    $home_owners[] = $home_owner_copy; // Adds homeowner array to return value
                }
            }
        }

        return $home_owners;
    }

    /**
     * Parses a string containing a name which can consist of the following segments:
     * - a title (required)
     * - an initial or a first name (optional)
     * - a last name (required)
     *
     * @param string $name
     * @return array
     */
    private function parse_single_name(string $name): array
    {
        // Value for return
        $home_owner = [
            'title' => null,
            'first_name' => null,
            'initial' => null,
            'last_name' => null
        ];

        // Splits the name on spaces
        $name_elements = explode(' ', $name);

        /** Test if the first element is a valid title.
         *
         * Tests explicitly for false, otherwise the falsey value of 0 will prevent the first array element
         * [0] from matching
         * */
        if (array_search($name_elements[0], $this->titles) !== false) {
            /** Title determined, set it in the homeowner array */
            $home_owner['title'] = $name_elements[0];
            /** Remove title element from array */
            unset($name_elements[0]);
            /** Reindex array, ensuring a 0 indexed element */
            $name_elements = array_values($name_elements);
        }

        /** The last element of the $name_elements array is assumed to be be the last name */
        $home_owner['last_name'] = array_pop($name_elements);

        /** If there are any remaining elements, they are assumed to be initials or first names */
        if (!empty($name_elements)) {
            /** Concatenate the remaining array elements, in case there is more than one */
            $name = implode(' ', $name_elements);
            /** Remove any periods from the $name to make it easier to identify an initial */
            $name = str_replace('.', '', $name);

            if (strlen($name) > 1) {
                /**
                 * A multiple character string is treated as a first name.
                 * Set the first_name property of the homeowner array
                 */
                $home_owner['first_name'] = $name;
            } else {
                /**
                 * A single character string is treated as an initial.
                 * Set the initial property of the homeowner array
                 */
                $home_owner['initial'] = $name;
            }
        }

        return $home_owner;
    }

    /**
     * Determines if the passed array contains name with paired titles (.e.g. 'Mr and Mrs Smith')
     * The original name string should be split on delimeters such as 'and' or '&', and the resulting array
     * passed in to this function. The array will have the delimeters eliminated.
     *
     * This test loops over the array to establish if any adjacent pair in the array meets the following criteria:
     *
     *  - The first element of the pair ends with a title as defined in the $this->titles property
     *    AND
     *  - The second element of the pair begins with a title as defined in the $this->titles property
     *
     * ## Example 1
     *
     * An original string of 'Mr and Mrs Will Smith' is passed to this function as: `['Mr', 'Mrs Will Smith']`
     *
     * - $name_elements[0] ends with a title
     * - $name_elements[1] begins with a title.
     *
     * This array _does_ meets the test.
     *
     * ## Example 2
     *
     * An original string of 'Mr Alan Smith and Ms Andrea Hill' is passed to this function
     * as: ['Mr Alan Smith', 'Ms Andrea Hill']
     *
     * - $name_elements[1] begins with a title
     * - $name_elements[0] does not end with a title.
     *
     * This array _does not_ meet the test.
     *
     * @param string[] $name_elements
     * @return bool
     */
    private function paired_titles_exists(array $name_elements): bool
    {
        /** @var string $concatenated_titles    Concatonated string of titles, formatted with pipes for regex use */
        $concatenated_titles = implode("|", $this->titles);

        for ($i = 0; $i < count($name_elements) - 1; $i++) {
            if (
                $i + 1 <= count($name_elements) - 1 && // Is there an element after this one?
                preg_match("/({$concatenated_titles})$/", trim($name_elements[$i])) && // Match a title at the end of the current element
                preg_match("/^{$concatenated_titles}/", trim($name_elements[$i + 1])) // and a title at the beginning of the next element
            ) {
                /** A single match is enough to return true and exit the function */
                return true;
            }
        }

        /** Return false if no matches are found */
        return false;
    }
}

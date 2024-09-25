<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Word Frequency Counter</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Word Frequency Counter</h1>
    <form method="post">
        <label for="text">Paste your text here:</label><br>
        <textarea id="text" name="text" rows="10" cols="50" required></textarea><br><br>
        
        <label for="sort">Sort by frequency:</label>
        <select id="sort" name="sort">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select><br><br>
        
        <label for="limit">Number of words to display:</label>
        <input type="number" id="limit" name="limit" value="10" min="1" required><br><br>
        
        <input type="submit" value="Calculate Word Frequency">
    </form>

    <?php
    // Define stop words
    $stopWords = [
        'the', 'and', 'in', 'of', 'to', 'a', 'is', 'that', 'with', 'on', 'for', 'it', 'as', 'are', 'this', 'by', 'from'
    ];

    // Function to tokenize the text
    function tokenizeText($text) {
        // Remove punctuation and convert to lowercase
        $text = strtolower(preg_replace('/[^\w\s]/', '', $text));
        // Split text into an array of words and filter out empty values
        return array_filter(explode(' ', $text), fn($word) => !empty($word));
    }

    // Function to calculate word frequencies
    function calculateWordFrequencies($words, $stopWords) {
        $frequencies = [];
        foreach ($words as $word) {
            if (!in_array($word, $stopWords) && $word != '') {
                if (isset($frequencies[$word])) {
                    $frequencies[$word]++;
                } else {
                    $frequencies[$word] = 1;
                }
            }
        }
        return $frequencies;
    }

    // Function to sort word frequencies
    function sortFrequencies($frequencies, $sortOrder) {
        if ($sortOrder == 'asc') {
            asort($frequencies);
        } else {
            arsort($frequencies);
        }
        return $frequencies;
    }

    // Main processing
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get input data
        $text = $_POST['text'];
        $sortOrder = $_POST['sort'];
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;

        // Tokenize the text
        $words = tokenizeText($text);

        // Calculate word frequencies
        $frequencies = calculateWordFrequencies($words, $stopWords);

        // Sort the frequencies
        $sortedFrequencies = sortFrequencies($frequencies, $sortOrder);

        // Limit the number of words to display
        $sortedFrequencies = array_slice($sortedFrequencies, 0, $limit);

        // Display the result
        if (!empty($sortedFrequencies)) {
            echo "<h2>Word Frequency Results</h2>";
            echo "<ul>";
            foreach ($sortedFrequencies as $word => $count) {
                echo "<li><strong>$word</strong>: $count</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No words found or all words are stop words.</p>";
        }
    }
    ?>
</body>
</html>

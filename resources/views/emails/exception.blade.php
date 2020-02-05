<h3>Error Log Informtion from User Management:</h3>
<p>
  <strong>Date:</strong> {{ date('M d, Y H:iA') }}
</p>
<p>
  <strong>Message:</strong> {{ $content->getMessage() }}
</p>
<p>
<strong>Code:</strong> {{ $content->getCode() }}
</p>
<p>
<strong>File:</strong> {{ $content->getFile() }}
</p>
<p>
<strong>Line:</strong> {{ $content->getLine() }}
</p>
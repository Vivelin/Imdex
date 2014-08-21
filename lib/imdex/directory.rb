module Imdex
  ##
  # Represents a directory inside the public folder.
  #
  class Directory
    class << self
      # Gets or sets the full path to the public folder.
      attr_accessor :public_folder

      # Gets or sets a list of strings representing regular expressions that 
      # match files to ignore.
      attr_accessor :ignore_list

      # Gets or sets a regular expression that determines what images to show.
      attr_accessor :include_pattern
    end

    # Gets the unescaped URI path for the current directory.
    attr_reader :path

    ##
    # Initializes a new instance of the Imdex::Directory class for the specified
    # URI path.
    #
    def initialize(path)
      @path = unescape path
    end

    ##
    # Gets an array of components that the path to the current directory 
    # consists of, or an empty array.
    #
    def components
      @path.split("/").reject!(&:empty?) || []
    end

    ##
    # Gets the name of the current directory.
    #
    def name
      components.pop
    end

    ##
    # Constructs HTML code to represent the current path as a breadcrumb using
    # Semantic UI.
    #
    def breadcrumb_html
      parts = components

      # Remove the current component as it's handled separately
      curr = parts.pop

      html = %(<div class="ui breadcrumb">\n)

      # Include the public folder itself to make the root clickable
      if curr
        html << %(  <a class="section" href="/">#{ root_name }</a>\n)
      else
        html << %(  <div class="active section">#{ root_name }</div>\n)
      end

      # Add a divider and section for every component
      parts.each_index do |i|
        url = parts.take(i + 1).collect{ |x| u(x) }.join("/")

        html << %(  <div class="divider"> / </div>\n)
        html << %(  <a class="section" href="/#{ url }/">#{ h parts[i] }</a>\n)
      end

      # Add the current component as active section
      html << %(  <div class="divider"> / </div>\n)
      html << %(  <div class="active section">#{ h curr }</div>\n) unless curr.nil?

      html << %(</div>\n)
    end

    ##
    # Gets a list of subdirectories in the current directory.
    #
    def directories
      Dir.entries(file_path).select do |entry|
        full_path = translate_full_path(entry)
        path = translate_path(entry)

        File.directory?(full_path) unless ignore?(path)
      end
    end

    ##
    # Gets a list of files that match the include_pattern.
    #
    def entries
      Dir.entries(file_path).select do |entry|
        path = translate_path(entry)

        include?(path) unless ignore?(path)
      end
    end

    ##
    # Translates `name` into a path relative to the public folder, e.g. 
    # "titties.jpg" => "/NANO/Capture/titties.jpg".
    #
    def translate_path(name)
      File.join(@path, name)
    end

    private

    ##
    # Translates `name` into an absolute path to the public folder, e.g.
    # "titties.jpg" => "/path/to/Imdex/public/NANO/Capture/titties.jpg".
    #
    def translate_full_path(name)
      File.join(self.class.public_folder, translate_path(name))
    end

    ##
    # Determines whether or not the specified value matches the include_pattern.
    #
    def include?(value)
      self.class.include_pattern.match(value)
    end

    ##
    # Determines whether or not the specified value should be ignored.
    #
    def ignore?(value)
      self.class.ignore_list.each do |ignore|
        if Regexp.new(ignore) =~ value
          return true
        end
      end

      return false
    end

    ##
    # Determines the full path to the current directory.
    #
    def file_path
      File.join(self.class.public_folder, @path)
    end

    ##
    # Determines the name of the public folder to show in the breadcrumb
    #
    def root_name
      File.basename(self.class.public_folder)
    end

    ##
    # Escapes HTML.
    #
    def h(text)
      Rack::Utils.escape_html(text)
    end

    ##
    # Escapes URIs.
    #
    def u(text)
      Rack::Utils.escape_path(text)
    end

    ##
    # Unescapes URIs.
    #
    def unescape(text)
      Rack::Utils.unescape(text)
    end
  end
end
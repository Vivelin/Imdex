module Imdex
  ##
  # Represents an image inside the public folder.
  #
  class Image
    class << self
      # Gets or sets the full path to the public folder.
      attr_accessor :public_folder
    end

    attr_reader :path, :name

    ##
    # Initializes a new instance of the Imdex::Image class for the image 
    # specified by `name` in the directory specified by `path`.
    #
    def initialize(path, name)
      @path = path
      @name = name
    end

    ##
    # Determines whether the image exists.
    #
    def exists?
      File.exists?(file_path)
    end

    ##
    # Determines whether the image can be deleted.
    #
    def deletable?
      exists? && File.writable?(file_path)
    end

    ##
    # Gets the full path to the image on disk.
    #
    def file_path
      File.join(self.class.public_folder, @path, @name)
    end
  end
end
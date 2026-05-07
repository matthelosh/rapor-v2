self.onmessage = async function(event) {
    const { url, options } = event.data;
  
    try {
      const response = await fetch(url, options);
      const data = await response.json();
  
      // Send the response data back to the main thread
      self.getData({ success: true, data });
    } catch (error) {
      // Handle any error and send it back to the main thread
      self.getData({ success: false, error: error.message });
    }
  };
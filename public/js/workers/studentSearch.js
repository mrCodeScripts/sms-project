self.onmessage = async function(e) {
    const query = e.data;
    self.postMessage("shit");
    // console.log(LRN);
    // try {
    //     const response = await fetch("/request/superAdmin/searchStudent/", {
    //         method: "POST",
    //         headers: { "Content-Type": "application/json" },
    //         body: JSON.stringify({ data: LRN })
    //     });
    //     if (!response.ok) throw new Error("Failed to fetch student data");
    //     const data = await response.json();
    //     console.log(data);
    //     self.postMessage(data);
    // } catch (error) {
    //     self.postMessage({ error: error.message });
    // }
};
